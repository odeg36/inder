<?php

namespace AdminBundle\Service;

use FOS\UserBundle\Util\CanonicalizerInterface;

class CustomCanonicalizer implements CanonicalizerInterface {

    /**
     * {@inheritdoc}
     */
    public function canonicalize($string) {
        return $string;
    }

}
