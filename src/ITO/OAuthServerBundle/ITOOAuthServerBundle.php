<?php

namespace ITO\OAuthServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ITOOAuthServerBundle extends Bundle {

    public function getParent() {
        return 'FOSOAuthServerBundle';
    }

}
