<?php

declare(strict_types=1);

namespace Ghostwriter\PsalmPlugin\Hook\Psr\Container\ContainerInterface;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use Psalm\Plugin\EventHandler\AfterMethodCallAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterMethodCallAnalysisEvent;
use Psalm\Type\Atomic\TClassString;
use Psalm\Type\Atomic\TLiteralClassString;
use Psalm\Type\Atomic\TLiteralString;
use Psalm\Type\Atomic\TNamedObject;
use Psalm\Type\Atomic\TTemplateParam;
use Psalm\Type\Atomic\TTemplateParamClass;
use Psalm\Type\Union;
use Psr\Container\ContainerInterface;

use function explode;

final class GetMethodAfterMethodCallAnalysisHook implements AfterMethodCallAnalysisInterface
{
    public static function afterMethodCallAnalysis(AfterMethodCallAnalysisEvent $event): void
    {
        if ($event->getReturnTypeCandidate() === null) {
            return;
        }

        $expr = $event->getExpr();

        if (! $expr instanceof MethodCall) {
            return;
        }

        [$className, $methodName] = explode('::', $event->getDeclaringMethodId());

        if ('get' !== $methodName) {
            return;
        }

        $codebase = $event->getCodebase();

        if (ContainerInterface::class !== $className && ! $codebase->classImplements(
            $className,
            ContainerInterface::class
        )) {
            return;
        }

        $arg = $expr->args[0] ?? null;
        if (! $arg instanceof Arg) {
            return;
        }

        $type = $event->getStatementsSource()->getNodeTypeProvider()->getType($arg->value);
        if (null === $type) {
            return;
        }

        $returnTypeCandidates = [];

        foreach ($type->getAtomicTypes() as $atomicType) {
            if ($atomicType instanceof TLiteralClassString) {
                $returnTypeCandidates[] = new TNamedObject($atomicType->value);

                continue;
            }

            if ($atomicType instanceof TTemplateParamClass) {
                $returnTypeCandidates[] = new TTemplateParam(
                    $atomicType->param_name,
                    new Union([$atomicType->as_type ?? new TNamedObject($atomicType->as)]),
                    $atomicType->defining_class
                );

                continue;
            }

            if ($atomicType instanceof TLiteralString && $codebase->classOrInterfaceExists($atomicType->value)) {
                $returnTypeCandidates[] = new TNamedObject($atomicType->value);

                continue;
            }

            if (! $atomicType instanceof TClassString) {
                continue;
            }

            if (null === $atomicType->as_type) {
                continue;
            }

            $returnTypeCandidates[] = $atomicType->as_type;
        }

        if (empty($returnTypeCandidates)) {
            return;
        }

        $event->setReturnTypeCandidate(new Union($returnTypeCandidates));
    }
}
