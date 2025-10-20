<?php

declare(strict_types=1);

namespace App\Request\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RequestDtoResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return str_starts_with(
            $argument->getType() ?? '',
            'App\\Request\\DTO\\'
        );
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dtoClass = $argument->getType();

        $content = $request->getContent();

        if (empty($content) && !empty($request->request->all())) {
            $content = json_encode($request->request->all());
        }

        if (empty($content)) {
            throw new BadRequestHttpException('Empty request body');
        }

        $dto = $this
            ->serializer
            ->deserialize(
                $content,
                $dtoClass,
                'json'
            );

        $errors = $this
            ->validator
            ->validate($dto);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new BadRequestHttpException(message: json_encode($messages, JSON_UNESCAPED_UNICODE));
        }

        yield $dto;
    }
}
