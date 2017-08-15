<!doctype html>
<html id = 'atlasapp'>
<head>
<meta charset="utf-8">
<title>Atlas Search</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- styleSheets -->
    <link rel="stylesheet" type="text/css" href="/assets/css/angular-material.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="/assets/css/styles.css">
    <link rel="stylesheet" href="node_modules/ng-dialog/css/ngDialog.min.css" type="text/css" />
    <!-- Node Modules -->
    <script src="/assets/lib/jquery.min.js"></script>
    <script src="/node_modules/angular/angular.min.js"></script>
    <script src="/node_modules/angular-animate/angular-animate.min.js"></script>
    <script src="/node_modules/angular-aria/angular-aria.min.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="/node_modules/angular-material/angular-material.js"></script>
    <script src="/node_modules/angular-messages/angular-messages.min.js"></script>
    <script src="/node_modules/oclazyload/dist/ocLazyLoad.min.js"></script>
    <script src="/node_modules/ngstorage/ngStorage.min.js"></script>
    <script src="node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js"></script>
    <script src="node_modules/ng-dialog/js/ngDialog.min.js"></script>

    <script type="text/javascript" src="/app/app.module.js"></script>
    <script type="text/javascript" src="/app/app.config.js"></script>
    <!-- Library -->
    <script src="/assets/lib/svg-assets-cache.js"></script>  

    <!-- Services -->
    <script type="text/javascript" src="/app/services/resource.service.js" defer></script>
    <script type="text/javascript" src="/app/services/common.service.js" defer></script>
    <script type="text/javascript" src="/app/services/endpoint.service.js" defer></script>
    <script type="text/javascript" src="/app/services/data.service.js" defer></script>
    <script type="text/javascript" src="/app/services/message.service.js" defer></script>
    <script type="text/javascript" src="/app/services/session.service.js" defer></script>
    <script type="text/javascript" src="/app/services/access.service.js" defer></script>

    <!-- Directives -->
    <script type="text/javascript" src="/app/components/header/header.directive.js" defer></script>
    <script type="text/javascript" src="/app/components/header/search/jobpopup.directive.js" defer></script>
    <script type="text/javascript" src="/app/components/header/search/condidatespopup.directive.js" defer></script>
    <script type="text/javascript" src="/app/components/header/search/searchsection.Directive.js" defer></script>
    <script type="text/javascript" src="/app/components/footer/footer.directive.js" defer></script>
    <script type="text/javascript" src="/app/components/sidebar/sidebar.directive.js" defer></script>
    <script type="text/javascript" src="/app/components/validation.directive.js" defer></script>
    <script type="text/javascript" src="/app/components/onenter.directive.js" defer></script>

    <!--Controller-->
    <script type="text/javascript" src="/app/profile/profile.edit.controller.js"  defer></script>
    <script type="text/javascript" src="/app/profile/changePassword.controller.js" defer></script>
    <!--Resources-->
    

<base href="/">
</head>
    <body>
		<div class="progress-lodar main" ng-if='loader' >
			<div class="overlay"></div>
			<md-progress-circular  class="md-accent" md-diameter="80"></md-progress-circular>
		</div>
        <div ui-view></div>
        <script src="/assets/lib/ng-scrollable.js"></script>
        <link rel="stylesheet" type="text/css" href="/assets/css/ng-scrollable.min.css">
        <script src="/assets/lib/ng-scrollbar.js"></script>
        <link rel="stylesheet" type="text/css" href="/assets/css/ng-scrollbar.css">
    </body>
</html>

<style type="text/css">
    .ngdialog{    z-index: 82;}
    .ngdialog-overlay{z-index: 81}
    .ngdialog .ngdialog-content{margin:3% auto;  float: none;  background: none; position: relative;z-index:90;}
    .ngdialog .ngdialog-content .dialog-box{margin: 0 auto;   float: none;  background: #fff;}
</style>