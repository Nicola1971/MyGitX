<?php
if (IN_MANAGER_MODE != 'true') {
	die('<h1>ERROR:</h1><p>Please use the MODx Content Manager instead of accessing this file directly.</p>');
}

$output = "<html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Bootstrap - Prebuilt Layout</title>

<!-- Bootstrap -->
<link href='../assets/modules/mygitx/css/bootstrap.min.css' rel='stylesheet'>
<link href='media/style/common/font-awesome/css/font-awesome.min.css' rel='stylesheet'>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<script src='../assets/modules/mygitx/js/jquery-1.11.2.min.js'></script>
<script src='../assets/modules/mygitx/js/bootstrap.js'></script>
<script src='../assets/modules/mygitx/js/github.js' type='text/javascript'></script>
</head>
<script type='text/javascript'>
jQuery(document).ready(function ($) {
  $(function() {
    $('#github-projects').loadRepositories('$github_username');
  });
});
</script>
<body style='overflow-x:hidden';>
<nav class='navbar navbar-default'>
  <div class='container-fluid'> 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class='navbar-header'>
      <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#defaultNavbar1'><span class='sr-only'>Toggle navigation</span><span class='icon-bar'></span><span class='icon-bar'></span><span class='icon-bar'></span></button>
      <a class='navbar-brand' href='#'><i class='fa fa-github fa-lg' aria-hidden='true'></i> MyGitX</a></div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class='collapse navbar-collapse' id='defaultNavbar1'>
 
      <ul class='nav navbar-nav navbar-right'>
        <li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'><i class='fa fa-plus fa-lg' aria-hidden='true'></i><span class='caret'></span></a>
                  <ul class='dropdown-menu' role='menu'>
       
            <li><a target='_blank' href='https://github.com/new'>New repository</a></li>
            <li><a target='_blank' href='https://github.com/new/import'>Import repository</a></li>
            <li><a target='_blank' href='https://github.com/organizations/new'>New organization</a></li>
          </ul>
        </li>
        <li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'><i class='fa fa-user fa-lg' aria-hidden='true'></i><span class='caret'></span></a>
          <ul class='dropdown-menu' role='menu'>
       
            <li><a target='_blank' href='https://github.com/$github_username'>Your profile</a></li>
            <li><a target='_blank' href='https://github.com/stars'>Your stars</a></li>
            <li><a target='_blank' href='https://github.com/explore'>Explore</a></li>
            <li><a target='_blank' href='https://github.com/integrations'>Integrations</a></li>
            <li><a target='_blank' href='https://help.github.com'>Help</a></li>
            <li class='divider'></li>
            <li><a target='_blank' href='https://github.com/settings/profile'>Settings</a></li>
            <li><a target='_blank' href='https://github.com/logout'>Sign out</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- /.navbar-collapse --> 
  </div>
  <!-- /.container-fluid --> 
</nav>
<div class='container-fluid'>
<ul class='nav nav-tabs'>
  <li class='active'><a data-toggle='tab' href='#home'>MyGit</a></li>
  <li><a data-toggle='tab' href='#searchuser'>Search user</a></li>
</ul>

<div class='tab-content'>
  <div id='home' class='tab-pane fade in active'>
    <h3 class='text-center'>$github_username github repositories </h3>
      <div class='row text-left'>
      <div class='container'>
    <div class='col-md-12'><div id='github-projects'></div></div>
  </div>
   </div>
    </div>
   <div id='searchuser' class='tab-pane fade'>
  <div class='row'>
    <div class='text-center col-md-12'>
      <div class='well'><div class='container' id='w'>
    <h1>Search repository by github username</h1>
    <p>Enter a single Github username below and click the button to display profile info via JSON.</p>
    
    <input type='text' name='ghusername' id='ghusername' placeholder='Github username...'>
    
    <a class='btn btn-default' href='#' id='ghsubmitbtn'>Pull User Data</a>
    
    <div id='ghapidata' class='clearfix'></div>
  </div>
</div>
    </div>
  </div>
  </div>
  </div>
  <hr>
  <div class='row'>
    <div class='text-center col-md-6 col-md-offset-3'>
      <h4><i class='fa fa-github fa-lg' aria-hidden='true'></i></h4>
      <p>MyGitX for Evolution CMS</p>
    </div>
  </div>
  <hr>
</div>

<script type='text/javascript'>
$(function(){
  $('#ghsubmitbtn').on('click', function(e){
    e.preventDefault();
    $('#ghapidata').html('<div id='loader'><img src='css/loader.gif' alt='loading...'></div>');
    
    var username = $('#ghusername').val();
    var requri   = 'https://api.github.com/users/'+username;
    var repouri  = 'https://api.github.com/users/'+username+'/repos';
    
    requestJSON(requri, function(json) {
      if(json.message == 'Not Found' || username == '') {
        $('#ghapidata').html('<h2>No User Info Found</h2>');
      }
      
      else {
        // else we have a user and we display their info
        var fullname   = json.name;
        var username   = json.login;
        var aviurl     = json.avatar_url;
        var profileurl = json.html_url;
        var location   = json.location;
        var followersnum = json.followers;
        var followingnum = json.following;
        var reposnum     = json.public_repos;
        
        if(fullname == undefined) { fullname = username; }
        
        var outhtml = '<h2>'+fullname+' <span class='smallname'>(@<a href=''+profileurl+'' target='_blank'>'+username+'</a>)</span></h2>';
        outhtml = outhtml + '<div class='ghcontent'><div class='avi'><a href=''+profileurl+'' target='_blank'><img src=''+aviurl+'' width='80' height='80' alt=''+username+''></a></div>';
        outhtml = outhtml + '<p>Followers: '+followersnum+' - Following: '+followingnum+'<br>Repos: '+reposnum+'</p></div>';
        outhtml = outhtml + '<div class='repolist clearfix'>';
        
        var repositories;
        $.getJSON(repouri, function(json){
          repositories = json;   
          outputPageContent();                
        });          
        
        function outputPageContent() {
          if(repositories.length == 0) { outhtml = outhtml + '<p>No repos!</p></div>'; }
          else {
            outhtml = outhtml + '<p><strong>Repos List:</strong></p> <ul>';
            $.each(repositories, function(index) {
              outhtml = outhtml + '<a href=''+repositories[index].html_url+'' target='_blank'><h3>'+repositories[index].name + '</h3></a>';
            });
            outhtml = outhtml + '</ul></div>'; 
          }
          $('#ghapidata').html(outhtml);
        } // end outputPageContent()
      } // end else statement
    }); // end requestJSON Ajax call
  }); // end click event handler
  
  function requestJSON(url, callback) {
    $.ajax({
      url: url,
      complete: function(xhr) {
        callback.call(null, xhr.responseJSON);
      }
    });
  }
});
</script>
</body>
</html>

";

echo $output;
?>