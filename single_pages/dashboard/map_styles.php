<?php

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;
use Concrete\Core\View\View;

/** @var string|null $styles */

$styles = $styles ?? null;

if ($styles === "[]" || $styles === "null") {
    $styles = null;
}

$app = Application::getFacadeApplication();
/** @var Form $form */
/** @noinspection PhpUnhandledExceptionInspection */
$form = $app->make(Form::class);
/** @var Token $token */
/** @noinspection PhpUnhandledExceptionInspection */
$token = $app->make(Token::class);

?>

<div class="ccm-dashboard-header-buttons">
    <?php /** @noinspection PhpUnhandledExceptionInspection */
    View::element("dashboard/help", [], "map_styles"); ?>
</div>

<?php \Concrete\Core\View\View::element("dashboard/did_you_know", [], "map_styles"); ?>

<form action="#" method="post">
    <?php echo $token->output("update_settings"); ?>

    <fieldset>
        <legend>
            <?php echo t("General"); ?>
        </legend>

        <div class="form-group">
            <?php echo $form->label("styles", t("JavaScript Style Array")); ?>
            <?php echo $form->textarea("styles", $styles); ?>

            <p class="help-block">
                <?php echo t("To create a custom style for your embedded Google Maps, visit %s and design your own look. Once you're done, simply copy the generated JSON and paste it into the style field.",
                    "<a href=\"https://mapstyle.withgoogle.com\" target=\"_blank\">https://mapstyle.withgoogle.com</a>"
                ); ?>
            </p>
        </div>
    </fieldset>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <?php echo $form->submit('save', t('Save'), ['class' => 'btn btn-primary float-end']); ?>
        </div>
    </div>
</form>
