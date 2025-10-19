<?php

declare(strict_types=1);

namespace App\src\Request\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final class RequestDtoResolver implements RequestDtoResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {}

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return str_starts_with(
            $argument->getType() ?? '',
            'App\\Request\\DTO\\'
        );
    }

    /**
     * @param Request $request
     * @param string $dtoClass
     *
     * @return iterable
     */
    public function resolve(Request $request, string $dtoClass): iterable
    {
        $dto = $this
            ->serializer
            ->deserialize(
                $request->getContent(),
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
            throw new BadRequestHttpException(
                message: json_encode($messages, JSON_UNESCAPED_UNICODE)
            );
        }

        yield $dto;
    }
}