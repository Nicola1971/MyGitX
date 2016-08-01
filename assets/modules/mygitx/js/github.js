// http://aboutcode.net/2010/11/11/list-github-projects-using-javascript.html

jQuery.githubUserRepositories = function(username, callback) {
  jQuery.getJSON("https://api.github.com/users/" + username + "/repos?&page=1&per_page=100&callback=?", callback);
}
 
jQuery.fn.loadRepositories = function(username) {
  this.html("<span><i class=\"fa fa-spinner fa-spin fa-lg\" aria-hidden=\"true\"></i> Querying GitHub for repositories...</span>");
 
  var target = this; 
  $.githubUserRepositories(username, function(data) {
    var repos = data.data;
    sortByNumberOfWatchers(repos);
 
    var list = $('<dl/>');
    target.empty().append(list);
    $(repos).each(function() {
      list.append('<dt><a target="_blank" href="'+ this.html_url +'"><h3>' + this.name + '</h3></a></dt>');
      list.append('<dd>' + this.description + '</dd>');
              list.append('<dd><br/><a class="btn btn-default" href="'+ this.html_url +'/archive/master.zip">Download Zip</a></dd><hr/>');
    });
  });
 
  function sortByNumberOfWatchers(repos) {
    repos.sort(function(a,b) {
      return b.watchers - a.watchers;
    });
  }
};


$(function(){
  $('#ghsubmitbtn').on('click', function(e){
    e.preventDefault();
    $('#ghapidata').html('<div id="loader"><i class="fa fa-spinner fa-spin fa-lg" aria-hidden="true"></i></div>');
    
    var username = $('#ghusername').val();
    var requri   = 'https://api.github.com/users/'+username;
    var repouri  = 'https://api.github.com/users/'+username+'/repos?&per_page=100';
    
    requestJSON(requri, function(json) {
      if(json.message == "Not Found" || username == '') {
        $('#ghapidata').html("<h2>No User Info Found</h2>");
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
        
        var outhtml = '<div class="container col-sm-12> <div class="col-sm-3><h2>'+fullname+' <span class="smallname">(@<a href="'+profileurl+'" target="_blank">'+username+'</a>)</span></h2>';
        outhtml = outhtml + '<div class="ghcontent"><div class="avi"><a href="'+profileurl+'" target="_blank"><img src="'+aviurl+'" width="80" height="80" alt="'+username+'"></a></div>';
        outhtml = outhtml + '<p>Followers: '+followersnum+' - Following: '+followingnum+'<br>Repos: '+reposnum+'</p></div></div>';
        outhtml = outhtml + '<div class="text-left repolist clearfix">';
        
        var repositories;
        $.getJSON(repouri, function(json){
          repositories = json;   
          outputPageContent();                
        });          
        
        function outputPageContent() {
          if(repositories.length == 0) { outhtml = outhtml + '<p>No repos!</p></div>'; }
          else {
            outhtml = outhtml + '<div class="col-sm-9><p><strong>Repos List:</strong></p> <div class="text-left">';
            $.each(repositories, function(index) {
        outhtml = outhtml + '<a href="'+repositories[index].html_url+'" target="_blank"><h3>'+repositories[index].name + '</h3></a>';
outhtml = outhtml + '<p>'+repositories[index].description+'</p>';
             outhtml = outhtml + '<p><a class="btn btn-default" href="'+repositories[index].html_url+'/archive/master.zip" target="_blank">Download Zip</a></p>';
                  outhtml = outhtml + '<hr/>'; 
            });
            outhtml = outhtml + '</div></div></div></div>'; 
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