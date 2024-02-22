console.log(document.getElementById('alert-btn'));
if(document.getElementById('alert-btn') !=null){
    const btn = document.getElementById('alert-btn');
    const msg = btn.parentNode
    btn.addEventListener("click", function(){
        msg.remove();
    })
}