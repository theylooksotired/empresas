<?php
/**
 * @class PlaceReportController
 *
 * This class is the controller for the PlaceReport objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion
 * @version 3.0.1
 */
class PlaceReport_Controller extends Controller
{

    public function getContent()
    {
        $this->ui = new Navigation_Ui($this);
        switch ($this->action) {
            case 'reportar':
                switch ($this->id) {
                    default:
                        $this->layout_page = 'simple';
                        $this->head = '
                            ' . Navigation_Controller::activateJsHeader() . '
                            <meta name="robots" content="noindex,nofollow"/>';
                        $place = (new Place)->read($this->id);
                        if ($place->id() != '') {
                            $this->title_page = 'Reportar una empresa';
                            $this->metaDescription = 'Reportar una empresa del ' . Parameter::code('meta_title_page');
                            $placeReportForm = new PlaceReport_Form();
                            if (count($this->values) > 0) {
                                $placeReport = new PlaceReport($this->values);
                                if (count($placeReport->validateReCaptchaV3()) > 0) {
                                    Session::flashError('Hay un error de verificación de su identidad, por favor vuelva a intentarlo.');
                                } else {
                                    $placeReport->set('id_place', $place->id());
                                    $persist = $placeReport->persist();
                                    if ($persist['status'] == StatusCode::OK) {
                                        header('Location: ' . url('reportes/reportar/gracias'));
                                        exit();
                                    } else {
                                        Session::flashError('Lo sentimos, por favor revise el formulario.');
                                        $placeReportForm = new PlaceReport_Form($persist['values'], $persist['errors']);
                                    }
                                }
                            }
                            $this->content = '
                                <div class="report">
                                    <div class="report_top_wrapper">
                                        <div class="report_top_image"><img src="' . ASTERION_BASE_URL . 'visual/img/warning.svg"/></div>
                                        <div class="report_top_info">
                                            <p>Por favor <strong>diganos claramente la razón</strong> por la que desea reportar esta información.</p>
                                            <p>De acuerdo a la gravedad o cantidad de reportes eliminaremos los registros de nuestro sitio web.</p>
                                        </div>
                                    </div>
                                    <div class="report_place">' . $place->showUi('Simple') . '</div>
                                    ' . $placeReportForm->createPublic() . '
                                </div>';
                            return $this->ui->render();
                        } else {
                            header('Location: ' . url(''));
                            exit();
                        }
                        break;
                    case 'gracias':
                        $this->header = '<meta name="robots" content="noindex,nofollow"/>';
                        $this->layout_page = 'message';
                        $this->message_image = 'warning';
                        $this->title_page = 'Gracias por su reporte';
                        $this->message = '<p>Vamos a analizar la situación y daremos de baja el registro de la empresa si es necesario.</p>';
                        return $this->ui->render();
                        break;
                }
                break;
        }
        return parent::getContent();
    }

}
