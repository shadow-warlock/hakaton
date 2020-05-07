const FIX_PROBLEM = "fix problem";

function fixProblem(id){
    let params = {"id":id};
    let myAjax = new MyAjax();
    myAjax.request = myAjax.REQUEST_TYPES.EMPTY;
    myAjax.params = params;
    myAjax.success = function(){
        location.reload();
    };
    myAjax.send(FIX_PROBLEM);
}