<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.6.22/dist/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.22/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.22/dist/js/uikit-icons.min.js"></script>
        
    <title>Account</title>
</head>
<body>
    

<section class="uk-section">
    <div class="uk-container uk-container-small">
        
        <div class="uk-card uk-card-default">
            <div class="uk-card-body">
                <p class="uk-text-lead">Password reset</p>
                
                <?php if (hasFlashData('message')): ?>
                <div class="uk-alert-primary" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p><?= getFlashData('message'); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (hasFlashData('error')): ?>
                <div class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p><?= getFlashData('error'); ?></p>
                </div>
                <?php endif; ?>
                
                <form id="alter-login-form" class="alter-login-form uk-grid-medium uk-child-width-1-1k" uk-grid action="<?= baseUrl("users/reset") ?>" method="POST" accept-charset="utf-8">
                    <?= csrf_field() ?>
                    <div>
                        <label for="email" class="uk-form-label">Existing eMail</label>
                        <input id="email" type="email" name="email" class="uk-input" value="<?= getForm('email') ?>">
                        <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->email ?? []) ?></p>
                    </div>
                   
                    <div>
                        <label for="password" class="uk-form-label">New password</label>
                        <input id="password" type="password" name="password" class="uk-input" value="">
                        <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->password ?? []) ?></p>
                    </div>
                    
                    <div class="uk-flex uk-flex-between uk-flex-middle">
                        <button class="uk-button uk-button-primary" type="submit">Reset password</button>
                        
                        <div>
                            <a class="uk-link" href="<?= baseUrl("users/register") ?>">Register new account</a>
                            <span>-</span>
                            <a class="uk-link" href="<?= baseUrl("users/login") ?>">Login to existing account</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</section>

</body>
</html>