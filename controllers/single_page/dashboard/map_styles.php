<?php /** @noinspection PhpUnused */

namespace Concrete\Package\MapStyles\Controller\SinglePage\Dashboard;

use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Form\Service\Validation;
use Concrete\Core\Page\Controller\DashboardSitePageController;
use Concrete\Core\Site\Config\Liaison;

class MapStyles extends DashboardSitePageController
{
    protected Liaison $config;
    /** @var Validation */
    protected Validation $formValidator;

    public function on_start()
    {
        parent::on_start();
        $this->config = $this->getSite()->getConfigRepository();
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->formValidator = $this->app->make(Validation::class);
    }

    public function view()
    {
        if ($this->request->getMethod() === "POST") {
            $this->formValidator->setData($this->request->request->all());
            $this->formValidator->addRequiredToken("update_settings");

            if ($this->formValidator->test()) {
                $styles = json_decode($this->request->request->get("styles"), true);

                if (!is_array($styles)) {
                    $styles = [];
                }

                $this->config->save("map_styles.styles",  $styles);

                if (!$this->error->has()) {
                    $this->set("success", t("The settings has been successfully updated."));
                }
            } else {
                /** @var ErrorList $errorList */
                $errorList = $this->formValidator->getError();

                foreach ($errorList->getList() as $error) {
                    $this->error->add($error);
                }
            }
        }

        $this->set("styles", json_encode($this->config->get("map_styles.styles", [])));
    }
}