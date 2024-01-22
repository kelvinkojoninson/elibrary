var _=function(){var u,f,b,k,c;const S=APP_URL+"/api/modules/groups",x=APP_URL+"/api/modules/add-group",w=APP_URL+"/api/modules/update-group",h=APP_URL+"/api/modules/delete-group",v=APP_URL+"/api/export",B=()=>{var r,s,t,e;const a=document.querySelector("#kt_modal_filter");var n=document.querySelector(".search-input");a&&(r=new bootstrap.Modal(a),s=$("#kt_modal_filter_form"),t=a.querySelector('[data-kt-table-filter="reload"]'),e=a.querySelector('[data-kt-table-filter="reset"]')),c=$(u).DataTable({info:!0,processing:!1,serverSide:!1,order:[],pageLength:20,columnDefs:[{orderable:!1,targets:0},{orderable:!1,targets:5,visible:permissions.modUpdate==1||permissions.modDelete==1}],ajax:{url:S,method:"GET",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),"Content-Type":"application/json"},error:function(o,i,l){Swal.fire({title:"Request Timeout",text:"Oops :( request timed out. This could be as a result of an internal server error or slow or no internet connection. In the meantime, you can check your internet connection, refresh the page or wait a few minutes. Please contact admin if it persists.",icon:"info",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})},data:function(o){return"modAction=modRead&modID="+permissions.modID+"&dt_start="+o.start+"&dt_length="+o.length+"&dt_draw="+o.draw+"&dt_search="+n.value+(s?"&"+s.serialize():"")},beforeSend:function(){t&&(t.setAttribute("data-kt-indicator","on"),t.disabled=!0)},complete:function(){t&&(t.removeAttribute("data-kt-indicator"),t.disabled=!1,r.hide())}},columns:[{className:"p-4",defaultContent:`<div class="form-check form-check-sm form-check-custom">
                                    <input class="form-check-input" type="checkbox" value="1" />
                                </div>`},{data:"id"},{data:"title",className:"text-nowrap"},{data:"dateCreated",className:"text-nowrap"},{data:"status",className:"text-center",render:function(o,i,l,d){return o==1?'<div class="bg-success fw-bolder">Active</div>':'<div class="bg-danger fw-bolder">Inactive</div>'}},{data:null,className:"text-center text-nowrap",render:function(o,i,l,d){var m="";return permissions.modUpdate==1&&(m+=`<span style="cursor:pointer" class="text-primary me-3" data-kt-table-filter="update_row">
                                        Edit
                                    </span>`),permissions.modDelete==1&&(m+=`<span style="cursor:pointer" class="text-danger me-3 delete-btn" data-action="delete" data-id="${o.id}" data-kt-table-filter="delete">
                                        Delete
                                    </span>`),m}}],buttons:[{extend:"print",exportOptions:{columns:[1,2,3,4]}},{extend:"copy",exportOptions:{columns:[1,2,3,4]}},{extend:"excel",exportOptions:{columns:[1,2,3,4]}},{extend:"csv",exportOptions:{columns:[1,2,3,4]}},{extend:"pdf",exportOptions:{columns:[1,2,3,4]}},{text:"Refresh",action:function(o,i,l,d){i.ajax.reload(!1,null)}}]}),c.on("draw",function(){p(),C(),A(),y()}),n&&n.addEventListener("keyup",function(o){(o.key==="Enter"||o.keyCode===13||o.which===13)&&(o.preventDefault(),c.search(n.value).draw())}),s&&(t&&t.addEventListener("click",function(o){c.ajax.reload()}),e&&e.addEventListener("click",function(){const o=document.querySelector('[data-kt-table-filter="form"]');o.querySelectorAll("select").forEach(l=>{$(l).val("").trigger("change")}),o.reset(),c.ajax.reload()}))},T=async()=>{if(permissions.modCreate==0)return;const r=document.querySelector("#kt_modal_add_common");if(!r)return;const s=new bootstrap.Modal(r),t=r.querySelector("form");if(!t)return;const e=r.querySelector('[data-action="submit"]');if(!e)return;const a=t.querySelectorAll("select");e.addEventListener("click",async n=>{n.preventDefault(),e.setAttribute("data-kt-indicator","on"),e.disabled=!0;let o=new FormData(t);o.append("modAction","modCreate"),o.append("modID",permissions.modID);try{const i=await fetch(x,{method:"POST",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:o}),{ok:l,msg:d}=await i.json();if(!l){Swal.fire({text:d,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}Swal.fire({text:d,icon:"success",allowOutsideClick:!1,buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}).then(m=>{m.isConfirmed&&(t.reset(),s.hide(),c.ajax.reload(),a.forEach(g=>$(g).val("").trigger("change")))})}catch(i){Swal.fire({text:i.message,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})}finally{e.removeAttribute("data-kt-indicator"),e.disabled=!1}})},O=async()=>{if(permissions.modUpdate==0)return;const r=document.querySelector("#kt_modal_update_common");if(!r)return;const s=new bootstrap.Modal(r),t=r.querySelector("form");if(!t)return;const e=r.querySelector('[data-action="submit"]');if(!e)return;const a=t.querySelectorAll("select");e.addEventListener("click",async n=>{n.preventDefault(),e.setAttribute("data-kt-indicator","on"),e.disabled=!0;let o=new FormData(t);o.append("modAction","modUpdate"),o.append("modID",permissions.modID);try{const i=await fetch(w,{method:"POST",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:o}),{ok:l,msg:d}=await i.json();if(!l){Swal.fire({text:d,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}Swal.fire({text:d,icon:"success",allowOutsideClick:!1,buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}).then(m=>{m.isConfirmed&&(t.reset(),s.hide(),c.ajax.reload(),a.forEach(g=>$(g).val("").trigger("change")))})}catch(i){Swal.fire({text:i.message,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})}finally{e.removeAttribute("data-kt-indicator"),e.disabled=!1}})},A=()=>{if(permissions.modUpdate==0)return;u.querySelectorAll('[data-kt-table-filter="update_row"]').forEach(s=>{s.addEventListener("click",function(t){t.preventDefault();const e=c.row($(t.target.closest("tbody tr"))).data();$("#kt_modal_update_common").modal("show"),$("#update-code").val(e.id),$("#update-title").val(e.title),e.status==1?document.getElementById("update-status").checked=!0:document.getElementById("update-status").checked=!1})})},C=async()=>{if(permissions.modDelete==0)return;u.querySelectorAll('[data-action="delete"]').forEach(s=>{s.addEventListener("click",async t=>{t.preventDefault();let e=JSON.stringify([t.currentTarget.getAttribute("data-id")]);Swal.fire({title:"Are you sure?",text:"Are you sure you want to delete selected record(s)?",icon:"warning",showCancelButton:!0,buttonsStyling:!1,confirmButtonText:"Yes, delete!",cancelButtonText:"No, cancel",customClass:{confirmButton:"btn fw-bold btn-danger",cancelButton:"btn fw-bold btn-active-light-primary"}}).then(async a=>{if(a.value){Swal.fire({text:"Deleting record(s)...",showConfirmButton:!1,allowEscapeKey:!1,allowOutsideClick:!1});try{const n=await fetch(h+"?modAction=modDelete&modID="+permissions.modID+"&selected="+e,{method:"POST",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}),{ok:o,msg:i}=await n.json();if(!o){Swal.fire({text:i,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}Swal.fire({text:i,icon:"success",allowOutsideClick:!1,buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}).then(l=>{c.ajax.reload()})}catch(n){Swal.fire({text:n.message,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})}finally{}}else a.dismiss==="cancel"&&Swal.fire({text:"Record was not deleted.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn fw-bold btn-primary"}})})})})},p=()=>{if(permissions.modDelete==0)return;const r=u.querySelectorAll('[type="checkbox"]');f=document.querySelector('[data-kt-table-toolbar="base"]'),b=document.querySelector('[data-kt-table-toolbar="selected"]'),k=document.querySelector('[data-kt-table-select="selected_count"]');const s=document.querySelector('[data-kt-table-select="delete_selected"]');r.forEach(t=>{t.addEventListener("click",function(){setTimeout(function(){y()},50)})}),s.addEventListener("click",function(){Swal.fire({text:"Are you sure you want to delete selected record(s)?",icon:"warning",showCancelButton:!0,buttonsStyling:!1,confirmButtonText:"Yes, delete!",cancelButtonText:"No, cancel",customClass:{confirmButton:"btn fw-bold btn-danger",cancelButton:"btn fw-bold btn-active-light-primary"}}).then(function(t){if(t.value){var e=[];r.forEach(n=>{var i=n.closest("tr").querySelector(".delete-btn");i&&n.checked&&e.push(i.getAttribute("data-id"))});var a=JSON.stringify(e);Swal.fire({text:"Deleting record(s)...",showConfirmButton:!1,allowEscapeKey:!1,allowOutsideClick:!1}),fetch(h+"?modAction=modDelete&modID="+permissions.modID+"&selected="+a,{method:"POST",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(function(n){return n.json()}).then(function(n){if(!n.ok){Swal.fire({text:n.msg,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}Swal.fire({text:"Record(s) deleted!.",icon:"success",buttonsStyling:!1,allowOutsideClick:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn fw-bold btn-primary"}}).then(function(){c.ajax.reload(),u.querySelectorAll('[type="checkbox"]')[0].checked=!1}).then(function(){y(),p()})}).catch(function(n){n&&Swal.fire({text:n,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})})}else t.dismiss==="cancel"&&Swal.fire({text:"Selected record(s) not deleted.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn fw-bold btn-primary"}})})})},y=()=>{const r=u.querySelectorAll('tbody [type="checkbox"]');let s=!1,t=0;r.forEach(e=>{e.checked&&(s=!0,t++)}),s?(k.innerHTML=t,f.classList.add("d-none"),b.classList.remove("d-none")):(f.classList.remove("d-none"),b.classList.add("d-none"))},E=function(){if(permissions.modReport==0)return;const r=document.getElementById("kt_modal_export");if(!r)return;const s=r.querySelector("#kt_modal_export_form"),t=new bootstrap.Modal(r);var e=FormValidation.formValidation(s,{fields:{format:{validators:{notEmpty:{message:"File format is required"}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap:new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row",eleInvalidClass:"",eleValidClass:""})}});const a=r.querySelector('[data-kt-modal-action="submit"]');a.addEventListener("click",function(n){n.preventDefault(),e&&e.validate().then(function(o){if(o=="Valid"){a.setAttribute("data-kt-indicator","on");var i=document.getElementById("export-format").value;a.setAttribute("data-kt-indicator","on"),a.disabled=!0,fetch(v+"?modAction=modReport&modID="+permissions.modID,{method:"POST",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),"Content-Type":"application/json"}}).then(function(l){return l.json()}).then(function(l){if(!l.ok){a.removeAttribute("data-kt-indicator"),a.disabled=!1,Swal.fire({text:l.msg,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}a.removeAttribute("data-kt-indicator"),a.disabled=!1,t.hide(),Swal.fire({text:"Data is ready to be exported!",icon:"success",allowOutsideClick:!1,buttonsStyling:!1,confirmButtonText:"Ok, Export!",customClass:{confirmButton:"btn btn-primary"}}).then(function(d){i==="print"?c.button(".buttons-print").trigger():i==="excel"?c.button(".buttons-excel").trigger():i==="csv"?c.button(".buttons-csv").trigger():i==="pdf"&&c.button(".buttons-pdf").trigger()})}).catch(function(l){l&&(a.removeAttribute("data-kt-indicator"),a.disabled=!1,Swal.fire({text:l,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}))})}else Swal.fire({text:"Sorry, looks like there are some errors detected, please try again.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})})})};return{init:function(){u=document.getElementById("kt_table_common"),u&&permissions.modRead!=0&&(B(),T(),O(),p(),E())}}}();KTUtil.onDOMContentLoaded(function(){_.init()});
