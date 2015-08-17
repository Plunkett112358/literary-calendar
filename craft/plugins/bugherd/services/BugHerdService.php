<?php
namespace Craft;

class BugHerdService extends BaseApplicationComponent {


  public function checkAccess() {

    $plugin = craft()->plugins->getPlugin('bugHerd');
    $settings = $plugin->getSettings();

    $showTab = false;

    if ($settings->getAttribute('frontEnd'))
    {

      if ($settings->getAttribute('publicAccess'))
      {
        if (craft()->userSession->isLoggedIn())
        {
          $showTab = true;
        }
      }
      else
      {
        if (craft()->userSession->checkPermission('accessCp'))
        {
          $showTab = true;
        }
      }

    } else {

      if ( craft()->request->isCpRequest() )
      {
        $showTab = true;
      }
    }

    return $showTab;

  }

  public function buildCode()
  {

    $plugin = craft()->plugins->getPlugin('bugHerd');
    $settings = $plugin->getSettings();

    $user = craft()->userSession->getUser();


    $code = "<script type='text/javascript'>

    var BugHerdConfig = {

        metadata: {

          logged_in: \"true\"
        },

        feedback: {
          tab_text: \"".$settings->getAttribute('tabText')."\"
        }
    ";

    if($user) {
      $code .=
        ",
          reporter: {
            email: \"".$user->email."\",
            required: \"true\"
          }";
    }

    $code .=  "
    };

(function (d, t) {
var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
bh.type = 'text/javascript';
bh.src = '//www.bugherd.com/sidebarv2.js?apikey=".$settings->getAttribute('projectKey')."';
s.parentNode.insertBefore(bh, s);
})(document, 'script');
</script>";

    return $code;
  }
}
