
/*  API */
function loadDocumentInfos(control){

        control.innerHTML = `
            <div id="div_proforma_validation" class="ms-2 bg-white p-2" style="border-top: 1px solid lightgrey;">
            <form id="form_doc_validation">

            </form>
        </div>`
    getInfos(document.getElementById("form_doc_validation"))
}


/*  FIN API */

