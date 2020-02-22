<?php


namespace App\Service;


use App\DTO\UserData;
use App\Form\UserType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormService
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function createUserForm(UserData $userData, Request $request, array $options = [])
    {
        $form = $this->formFactory->create(UserType::class, $userData, $options);
        $form->submit($request->request->all());

        return $form;
    }

    public function getFormErrors(FormInterface $form)
    {
        $errors = [];

        // Global
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }

        // Fields
        foreach ($form as $child/** @var Form $child */) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    if ($child->getErrors()->count() > 1) {
                        $errors[$child->getName()][] = $error->getMessage();
                    } else {
                        $errors[$child->getName()] = $error->getMessage();
                    }
                }
            }
        }

        return $errors;
    }
}
