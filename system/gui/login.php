<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $langArray['logininto']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.19.4/css/uikit.min.css" />
  </head>
  <body>

    <div class="uk-container uk-flex uk-flex-center uk-flex-middle" style="height: 100vh;">
      <div class="uk-card uk-card-default uk-card-body uk-width-1-3@m">
        <h3 class="uk-card-title uk-text-center"><?php echo $langArray['logininto']; ?> Diary</h3>
        <form class="uk-form-stacked" action="index.php?login=login" method="post">
          <input type="hidden" id="login" name="login" value="login" />
          <div class="uk-margin">
            <label class="uk-form-label" for="username"><?php echo $langArray['username']; ?></label>
            <div class="uk-form-controls">
              <input class="uk-input" id="username" type="text" name="username" placeholder="<?php echo $langArray['yourusername']; ?>">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label" for="password"><?php echo $langArray['password']; ?></label>
            <div class="uk-form-controls">
              <input class="uk-input" id="password" name="password" type="password" placeholder="<?php echo $langArray['yourpassword']; ?>">
            </div>
          </div>
          <div class="uk-margin uk-text-center">
            <button class="uk-button uk-button-primary" type="submit"><?php echo $langArray['login']; ?></button>
          </div>
        </form>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.19.4/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.19.4/js/uikit-icons.min.js"></script>
  </body>
</html>