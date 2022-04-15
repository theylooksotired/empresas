<?php
/**
 * @class PlaceCommentController
 *
 * This class is the controller for the PlaceComment objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion
 * @version 3.0.1
 */
class PlaceComment_Controller extends Controller
{

    public function getContent()
    {
        $this->ui = new Navigation_Ui($this);
        switch ($this->action) {
            case 'comentar':
                switch ($this->id) {
                    default:
                        $this->layout_page = 'simple';
                        $this->head = '
                            ' . Navigation_Controller::activateJsHeader() . '
                            <meta name="robots" content="noindex,nofollow"/>';
                        $place = (new Place)->read($this->id);
                        if ($place->id() != '') {
                            $this->title_page = 'Comentario en una empresa';
                            $this->meta_description = 'Comentar en una empresa del ' . Parameter::code('meta_title_page');
                            $placeCommentForm = new PlaceComment_Form();
                            if (count($this->values) > 0) {
                                $placeComment = new PlaceComment($this->values);
                                if (count($placeComment->validateReCaptchaV3()) > 0) {
                                    Session::flashError('Hay un error de verificación de su identidad, por favor vuelva a intentarlo.');
                                } else {
                                    $placeComment->set('id_place', $place->id());
                                    $placeComment->set('active', 0);
                                    $persist = $placeComment->persist();
                                    if ($persist['status'] == StatusCode::OK) {
                                        header('Location: ' . url('comentarios/comentar/gracias'));
                                        exit();
                                    } else {
                                        Session::flashError('Lo sentimos, por favor revise el formulario.');
                                        $placeCommentForm = new PlaceComment_Form($persist['values'], $persist['errors']);
                                    }
                                }
                            }
                            $this->content = '
                                <div class="report">
                                    <div class="report_top_wrapper">
                                        <div class="report_top_image"><img src="' . ASTERION_BASE_URL . 'visual/img/comment.svg"/></div>
                                        <div class="report_top_info">
                                            <p>Por favor deje su comentario sobre esta empresa o negocio.<br/>Vamos a revisarlo y lo publicaremos en un plazo máximo de 48 horas.</p>
                                            <p>Recuerde que no toleramos insultos ni agresiones.</p>
                                        </div>
                                    </div>
                                    <div class="report_place">
                                        ' . $place->showUi('Simple') . '
                                    </div>
                                    ' . $placeCommentForm->createPublic() . '
                                </div>';
                            return $this->ui->render();
                        } else {
                            header('Location: ' . url(''));
                            exit();
                        }
                        break;
                    case 'activar':
                        $this->layout_page = 'empty';
                        $comment = (new PlaceComment)->readCoded($this->extraId);
                        $this->title_page = 'Comentario activado';
                        if ($comment->id() != '') {
                            $comment->modifySimple('active', '1');
                            $place = (new Place)->read($comment->get('idPlace'));
                            $this->message = 'El comentario ha sido activado. Puede verlo en: ' . $place->link();
                        } else {
                            $this->message = 'El comentario no existe';
                        }
                        return $this->ui->render();
                        break;
                    case 'gracias':
                        $this->head = '<meta name="robots" content="noindex,nofollow"/>';
                        $this->layout_page = 'message';
                        $this->message_image = 'happythanks';
                        $this->title_page = 'Gracias por su comentario';
                        $this->message = '<p>Lo vamos a revisar y lo publicaremos en menos de 48 horas.</p>';
                        return $this->ui->render();
                        break;
                }
                break;
        }
        return parent::getContent();
    }

}
