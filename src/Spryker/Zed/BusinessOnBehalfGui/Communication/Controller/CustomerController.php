<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Controller;

use ArrayObject;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 */
class CustomerController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS_COMPANY_USER_CREATE = 'Customer has been assigned to business unit.';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_COMPANY_USER_CREATE = 'Customer has not been attached to business unit.';

    /**
     * @var string
     */
    protected const MESSAGE_COMPANY_NOT_FOUND = 'Company not found.';

    /**
     * @var string
     */
    protected const MESSAGE_CUSTOMER_NOT_FOUND = 'Customer not found.';

    /**
     * @var string
     */
    protected const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';

    /**
     * @var string
     */
    protected const URL_ATTACH_CUSTOMER_TO_BUSINESS_UNIT = '/business-on-behalf-gui/customer/attach-customer';

    /**
     * @var string
     */
    protected const PARAM_ID_CUSTOMER = 'id-customer';

    /**
     * @var string
     */
    protected const PARAM_ID_COMPANY = 'id-company';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function attachCustomerAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get(static::PARAM_ID_CUSTOMER));
        $idCompany = $this->castId($request->query->get(static::PARAM_ID_COMPANY));

        $form = $this->getFactory()
            ->getCustomerBusinessUnitAttachForm($idCustomer, $idCompany)
            ->handleRequest($request);

        if (!$form->getData()->getFkCompany()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_NOT_FOUND);

            return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        if (!$form->getData()->getFkCustomer()) {
            $this->addErrorMessage(static::MESSAGE_CUSTOMER_NOT_FOUND);

            return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleAttachCustomerActionIfFormIsSubmitted($form);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function handleAttachCustomerActionIfFormIsSubmitted(FormInterface $form)
    {
        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->create($form->getData());

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            $this->handleErrorMessages($companyUserResponseTransfer->getMessages());

            return $this->viewResponse([
                'form' => $form->createView(),
            ]);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_CREATE);

        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();
        $redirectUrl = Url::generate(static::URL_ATTACH_CUSTOMER_TO_BUSINESS_UNIT, [
            static::PARAM_ID_CUSTOMER => $companyUserTransfer->getFkCustomer(),
            static::PARAM_ID_COMPANY => $companyUserTransfer->getFkCompany(),
        ]);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ResponseMessageTransfer> $errorMessageTransfers
     *
     * @return void
     */
    protected function handleErrorMessages(ArrayObject $errorMessageTransfers): void
    {
        if (count($errorMessageTransfers) === 0) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_CREATE);

            return;
        }

        foreach ($errorMessageTransfers as $errorMessageTransfer) {
            $this->addErrorMessage($errorMessageTransfer->getText());
        }
    }
}
