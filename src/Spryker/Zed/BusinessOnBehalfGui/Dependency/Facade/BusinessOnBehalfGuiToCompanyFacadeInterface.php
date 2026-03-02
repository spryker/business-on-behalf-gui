<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade;

use Generated\Shared\Transfer\CompanyTransfer;

interface BusinessOnBehalfGuiToCompanyFacadeInterface
{
    public function getCompanyById(CompanyTransfer $companyTransfer): CompanyTransfer;

    public function findCompanyById(int $idCompany): ?CompanyTransfer;
}
