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
                <p class="uk-text-lead">Create account</p>
                
                <?php if (hasFlashData('error')): ?>
                <div class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p><?= getFlashData('error'); ?></p>
                </div>
                <?php endif; ?>

                <form enctype="multipart/form-data" id="alter-login-form" class="alter-login-form uk-grid-medium uk-child-width-1-1k" uk-grid action="<?= baseUrl("users/register") ?>" method="POST" accept-charset="utf-8">
                    <?= csrf_field() ?>
                    <div>
                        <label for="name" class="uk-form-label">Name</label>
                        <input id="name" type="text" name="name" class="uk-input" value="<?= getForm('name') ?>">
                        <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->name ?? []) ?></p>
                    </div>
                    
                    <div>
                        <label for="username" class="uk-form-label">Username</label>
                        <input id="username" type="text" name="username" class="uk-input" value="<?= getForm('username') ?>">
                        <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->username ?? []) ?></p>
                    </div>
                    
                    <div>
                        <label for="email" class="uk-form-label">eMail</label>
                        <input id="email" type="email" name="email" class="uk-input" value="<?= getForm('email') ?>">
                        <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->email ?? []) ?></p>
                    </div>
                    
                    <div>
                        <label for="" class="uk-form-label">Profile image</label>
                        <div class="uk-placeholder uk-margin-remove uk-text-center">
                            <span uk-icon="icon: cloud-upload"></span>
                            <span class="uk-text-middle">Set profile image -</span>
                            <div uk-form-custom>
                                <input name="avatar" type="file">
                                <span class="uk-link">Select avatar</span>
                            </div>
                        </div>
                        <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->avatar ?? []) ?></p>
                    </div>
                   
                    <div>
                        <label for="password" class="uk-form-label">Password</label>
                        <input id="password" type="password" name="password" class="uk-input" value="<?= getForm('password') ?>">
                        <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->password ?? []) ?></p>
                    </div>
                    
                    <div>
                        <label for="password" class="uk-form-label">Password repeat</label>
                        <input id="password" type="password" name="password_repeat" class="uk-input" value="<?= getForm('password_repeat') ?>">
                        <p class="uk-margin-remove uk-text-danger uk-text-small"><?= implode(', ', getFlashData('errors')->password_repeat ?? []) ?></p>
                    </div>
                    
                    <div class="uk-flex uk-flex-between uk-flex-middle">
                        <button class="uk-button uk-button-primary" type="submit">Register</button>
                        
                        <div>
                            <a class="uk-link" href="<?= baseUrl("users/login") ?>">I have an account</a>
                            <span>-</span>
                            <a class="uk-link" href="<?= baseUrl("users/reset") ?>">Reset password</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</section>

</body>
</html>