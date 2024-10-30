window.onload = compnp_displayNews

var compnp_targets;
var compnp_newsContent;
var compnp_remove = true;
var compnp_counter = 0;
var compnp_totalNews;


function compnp_displayNews() 
{
  compnp_targets = new Array();
  compnp_newsContent = new Array();
  compnp_initializeValues();
  compnp_totalNews = compnp_targets.length-1;
  compnp_createLink();
}

function compnp_createLink() {
  var compnp_tlink = document.getElementById('compnp_target');
  compnp_tlink.innerHTML = compnp_newsContent[compnp_counter];
  compnp_tlink.href = compnp_targets[compnp_counter];
  
  if (compnp_counter == compnp_totalNews) {
		compnp_counter = 0;
	} else {
		compnp_counter++;
	}
	setTimeout(compnp_createLink, compnp_delay);
}

