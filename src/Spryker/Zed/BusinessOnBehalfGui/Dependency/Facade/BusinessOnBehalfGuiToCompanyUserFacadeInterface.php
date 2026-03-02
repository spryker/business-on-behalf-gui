<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface BusinessOnBehalfGuiToCompanyUserFacadeInterface
{
    public function countActiveCompanyUsersByIdCustomer(CustomerTransfer $customerTransfer): int;

    public function deleteCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer;

    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    public function findCompanyUserById(int $idCompanyUser): ?CompanyUserTransfer;
}
