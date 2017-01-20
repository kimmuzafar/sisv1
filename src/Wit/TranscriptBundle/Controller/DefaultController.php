<?php

namespace Wit\TranscriptBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;

use Wit\ModelBundle\Entity\Course;
use Wit\ModelBundle\Entity\Marksheet;

/**
 * Quick News Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * Dashboard for transcripts, will display the statistics for automatically generated transcripts..
     * 
     * @Route("/")
     * 
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * TODO: from this page TOD or tobe decided will be ale to view individual transcript
     * 
     * @Route("/{marksheet_id}/view/")
     * 
     * @Template()
     */
    public function viewAction($marksheet_id)
    {
    	$em = $this->getDoctrine()->getManager();

    	$marksheetEntity = $em->getRepository('ModelBundle:Marksheet')->find($marksheet_id);
    	$gradingSystemEntities = $em->getRepository('ModelBundle:GradingSystem')->findBy(array());

        return array(
        	'marksheetEntity' => $marksheetEntity,
        	'gradingSystemEntities' => $gradingSystemEntities,
       	);

        // $html = $this->renderView('TranscriptBundle:Default:view.html.twig', array(
        //     'marksheetEntity' => $marksheetEntity,
        //     'gradingSystemEntities' => $gradingSystemEntities,
        // ));

        // return new Response(
        //     $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
        //     200,
        //     array(
        //         'Content-Type'          => 'application/pdf',
        //         'Content-Disposition'   => 'attachment; filename="file.pdf"'
        //     )
        // );
    }

    /**
     * TODO: Printable marksheet without header, footer and left navigation
     * 
     * @Route("/{marksheet_id}/print/")
     * 
     * @Template()
     */
    public function printAction($marksheet_id)
    {
        $em = $this->getDoctrine()->getManager();

        $marksheetEntity = $em->getRepository('ModelBundle:Marksheet')->find($marksheet_id);
        $gradingSystemEntities = $em->getRepository('ModelBundle:GradingSystem')->findBy(array());

        return array(
            'marksheetEntity' => $marksheetEntity,
            'gradingSystemEntities' => $gradingSystemEntities,
        );

        // $html = $this->renderView('TranscriptBundle:Default:view.html.twig', array(
        //     'marksheetEntity' => $marksheetEntity,
        //     'gradingSystemEntities' => $gradingSystemEntities,
        // ));

        // return new Response(
        //     $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
        //     200,
        //     array(
        //         'Content-Type'          => 'application/pdf',
        //         'Content-Disposition'   => 'attachment; filename="file.pdf"'
        //     )
        // );
    }

    /**
     * 
     * This action will generate PDF of selected transcript
     * 
     * @Route("/generate-pdf/for/{marksheet_id}/")
     * 
     * @Template()
     */
    public function generatePDFAction($marksheet_id)
    {
        //$this->get('knp_snappy.pdf')->generate('http://www.wit.edu.sa', '/pdf');
        exit;
    }
}
