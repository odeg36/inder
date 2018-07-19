<?php

namespace AdminBundle\Export;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Sonata\UserBundle\Entity\User;

trait CRUDControllerExtraExportTrait {

    /**
     * @param Request $request
     * @return Response
     */
    public function exportAction(Request $request = null) {
        $id = 0;
        $tipo = null;

        /* @var CRUDController $this */
        try {
            return parent::exportAction($request);
        } catch (\RuntimeException $e) {
            $format = $request->get('format');

            $filename = sprintf(
                    'export_%s_%s.%s', strtolower(substr($this->admin->getClass(), strripos($this->admin->getClass(), '\\') + 1)), date('Y_m_d_H_i_s', strtotime('now')), $format
            );

            $html = $this->renderView('AdminBundle:Export:list.html.twig', [
                'admin' => $this->admin,
                'admin_pool' => $this->get('sonata.admin.pool'),
                'deportistaId' => $id,
                'tipo' => $tipo
            ]);

            switch ($format) {
                case 'pdf':
                    return new Response(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html, [
                            'orientation' => 'portrait',
                            'encoding' => 'utf-8',
                            'margin-top'    => 10,
                            'margin-right'  => 5,
                            'margin-bottom' => 10,
                            'margin-left'   => 5
                        ]), 200, [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                        ]
                    );
                    break;
                case 'jpg':
                    return new Response(
                        $this->get('knp_snappy.image')->getOutputFromHtml($html, [
                            'encoding' => 'utf-8'
                        ]), 200, [
                        'Content-Type' => 'image/jpeg',
                        'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                            ]
                    );
                    break;
                default:
                    throw $e;
            }
        }
    }

    protected function getJpgOptions() {
        return ['width' => 2480, 'height' => 3508];
    }

    protected function getPdfOptions() {
//        return ['orientation' => 'landscape'];
        return [];
    }

}
