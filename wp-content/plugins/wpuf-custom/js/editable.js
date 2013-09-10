function editableContent () {
    document.getElementById("new-post-title").value =  document.getElementById("new-post-title-h1").innerHTML;
    document.getElementById("cf_Tagline").value =  document.getElementById("new-post-cf_Tagline-p").innerHTML;
    document.getElementById("new-post-desc").value =  document.getElementById("new-post-desc-p").innerHTML;
 return true;
}