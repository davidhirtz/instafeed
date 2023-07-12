<?php
/**
 * Error email on error by cron job.
 *
 * @var yii\web\View $this
 * @var MessageInterface $message
 * @var InstagramToken $instagram
 * @var string|null $error
 */

use app\models\InstagramToken;
use yii\mail\MessageInterface;

$this->title = Yii::t('app', 'Error');
?>
<p><?= Yii::t('app', 'An error occurred while refreshing the Instagram access token of {name}.', ['name' => $instagram->name]); ?></p>
<?php if ($error) {
    ?>
    <table>
        <tbody>
        <tr>
            <td><?= $error; ?></td>
        </tr>
        </tbody>
    </table>
    <?php
} ?>
<div class="btn-wrap">
    <a href="<?= $instagram->getAdminUrl(); ?>"
       class="btn btn-primary"><?= Yii::t('app', 'View account'); ?></a>
</div>
