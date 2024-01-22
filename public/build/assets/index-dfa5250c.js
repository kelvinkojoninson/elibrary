var _=function(){var m,b,p,k,c;const S=APP_URL+"/api/modules/children",x=APP_URL+"/api/modules",w=APP_URL+"/api/modules/update",h=APP_URL+"/api/modules/delete",v=APP_URL+"/api/export",B=()=>{var r,s,o,e;const a=document.querySelector("#kt_modal_filter");var n=document.querySelector(".search-input"),l=document.querySelector('[data-kt-table-filter="search-loader"]');a&&(r=new bootstrap.Modal(a),s=$("#kt_modal_filter_form"),o=a.querySelector('[data-kt-table-filter="reload"]'),e=a.querySelector('[data-kt-table-filter="reset"]')),c=$(m).DataTable({info:!0,processing:!0,serverSide:!0,order:[],pageLength:20,columnDefs:[{orderable:!1,targets:0},{orderable:!1,targets:9,visible:permissions.modUpdate==1||permissions.modDelete==1}],ajax:{url:S,method:"GET",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),"Content-Type":"application/json"},error:function(t,i,d){Swal.fire({title:"Request Timeout",text:"Oops :( request timed out. This could be as a result of an internal server error or slow or no internet connection. In the meantime, you can check your internet connection, refresh the page or wait a few minutes. Please contact admin if it persists.",icon:"info",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})},data:function(t){return"modAction=modRead&modID="+permissions.modID+"&dt_start="+t.start+"&dt_length="+t.length+"&dt_draw="+t.draw+"&dt_search="+n.value+(s?"&"+s.serialize():"")},beforeSend:function(){o&&(o.setAttribute("data-kt-indicator","on"),o.disabled=!0),l&&(l.setAttribute("data-kt-indicator","on"),l.disabled=!0)},complete:function(){o&&(o.removeAttribute("data-kt-indicator"),o.disabled=!1,r.hide()),l&&(l.removeAttribute("data-kt-indicator","on"),l.disabled=!1)}},columns:[{className:"p-4",defaultContent:`<div class="form-check form-check-sm form-check-custom">
                                    <input class="form-check-input" type="checkbox" value="1" />
                                </div>`},{data:"modID"},{data:"modLabel"},{data:"modName"},{data:null,render:function(t,i,d,f){return t.parentName?`<div class="badge badge-info">${t.parentName}</div>`:""}},{className:"text-center",data:"arrange"},{data:"modIcon",render:function(t,i,d,f){return t?`<div class="align-items-center">
                                <i class="ki-outline ki-${t} fs-3"></i>
                                ${t}
                            </div>`:""}},{data:"dateCreated",className:"text-nowrap"},{data:"modStatus",className:"text-center",render:function(t,i,d,f){let u="";return t==1&&(u+='<div class="bg-success fw-bolder">Active</div>'),u}},{data:null,className:"text-center text-nowrap",render:function(t,i,d,f){var u="";return permissions.modUpdate==1&&(u+=`<span style="cursor:pointer" class="text-primary me-3" data-kt-table-filter="update_row">
                                        Edit
                                    </span>`),permissions.modDelete==1&&(u+=`<span style="cursor:pointer" class="text-danger me-3 delete-btn" data-action="delete" data-id="${t.id}" data-kt-table-filter="delete">
                                        Delete
                                    </span>`),u}}],buttons:[{extend:"print",exportOptions:{columns:[1,2,3,4,5,6,7,8]}},{extend:"copy",exportOptions:{columns:[1,2,3,4,5,6,7,8]}},{extend:"excel",exportOptions:{columns:[1,2,3,4,5,6,7,8]}},{extend:"csv",exportOptions:{columns:[1,2,3,4,5,6,7,8]}},{extend:"pdf",exportOptions:{columns:[1,2,3,4,5,6,7,8]}},{text:"Refresh",action:function(t,i,d,f){i.ajax.reload(!1,null)}}]}),c.on("draw",function(){y(),E(),C(),g()}),n&&n.addEventListener("keyup",function(t){(t.key==="Enter"||t.keyCode===13||t.which===13)&&(t.preventDefault(),c.search(n.value).draw())}),s&&(o&&o.addEventListener("click",function(t){c.ajax.reload()}),e&&e.addEventListener("click",function(){const t=document.querySelector('[data-kt-table-filter="form"]');t.querySelectorAll("select").forEach(d=>{$(d).val("").trigger("change")}),t.reset(),c.ajax.reload()}))},T=async()=>{if(permissions.modCreate==0)return;const r=document.querySelector("#kt_modal_add_common");if(!r)return;const s=new bootstrap.Modal(r),o=r.querySelector("form");if(!o)return;const e=r.querySelector('[data-action="submit"]');if(!e)return;const a=o.querySelectorAll("select");e.addEventListener("click",async n=>{n.preventDefault(),e.setAttribute("data-kt-indicator","on"),e.disabled=!0;let l=new FormData(o);l.append("modAction","modCreate"),l.append("modID",permissions.modID);try{const t=await fetch(x,{method:"POST",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:l}),{ok:i,msg:d}=await t.json();if(!i){Swal.fire({text:d,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}Swal.fire({text:d,icon:"success",allowOutsideClick:!1,buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}).then(f=>{f.isConfirmed&&(o.reset(),s.hide(),c.ajax.reload(),a.forEach(u=>$(u).val("").trigger("change")))})}catch(t){Swal.fire({text:t.message,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})}finally{e.removeAttribute("data-kt-indicator"),e.disabled=!1}})},A=async()=>{if(permissions.modUpdate==0)return;const r=document.querySelector("#kt_modal_update_common");if(!r)return;const s=new bootstrap.Modal(r),o=r.querySelector("form");if(!o)return;const e=r.querySelector('[data-action="submit"]');if(!e)return;const a=o.querySelectorAll("select");e.addEventListener("click",async n=>{n.preventDefault(),e.setAttribute("data-kt-indicator","on"),e.disabled=!0;let l=new FormData(o);l.append("modAction","modUpdate"),l.append("modID",permissions.modID);try{const t=await fetch(w,{method:"POST",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:l}),{ok:i,msg:d}=await t.json();if(!i){Swal.fire({text:d,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}Swal.fire({text:d,icon:"success",allowOutsideClick:!1,buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}).then(f=>{f.isConfirmed&&(o.reset(),s.hide(),c.ajax.reload(),a.forEach(u=>$(u).val("").trigger("change")))})}catch(t){Swal.fire({text:t.message,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})}finally{e.removeAttribute("data-kt-indicator"),e.disabled=!1}})},C=()=>{if(permissions.modUpdate==0)return;m.querySelectorAll('[data-kt-table-filter="update_row"]').forEach(s=>{s.addEventListener("click",function(o){o.preventDefault();const e=c.row($(o.target.closest("tbody tr"))).data();$("#kt_modal_update_common").modal("show"),$("#update-code").val(e.id),$("#update-modName").val(e.modName),$("#update-modLabel").val(e.modLabel),$("#update-arrange").val(e.arrange),$("#update-status").val(e.modStatus).trigger("change"),$("#update-parentMod").val(e.pmodID).trigger("change"),$("#update-modIcon").val(e.modIcon),e.modStatus==1?document.getElementById("update-status").checked=!0:document.getElementById("update-status").checked=!1})})},E=async()=>{if(permissions.modDelete==0)return;m.querySelectorAll('[data-action="delete"]').forEach(s=>{s.addEventListener("click",async o=>{o.preventDefault();let e=JSON.stringify([o.currentTarget.getAttribute("data-id")]);Swal.fire({title:"Are you sure?",text:"Are you sure you want to delete selected record(s)?",icon:"warning",showCancelButton:!0,buttonsStyling:!1,confirmButtonText:"Yes, delete!",cancelButtonText:"No, cancel",customClass:{confirmButton:"btn fw-bold btn-danger",cancelButton:"btn fw-bold btn-active-light-primary"}}).then(async a=>{if(a.value){Swal.fire({text:"Deleting record(s)...",showConfirmButton:!1,allowEscapeKey:!1,allowOutsideClick:!1});try{const n=await fetch(h+"?modAction=modDelete&modID="+permissions.modID+"&selected="+e,{method:"DELETE",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}),{ok:l,msg:t}=await n.json();if(!l){Swal.fire({text:t,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}Swal.fire({text:t,icon:"success",allowOutsideClick:!1,buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}).then(i=>{c.ajax.reload()})}catch(n){Swal.fire({text:n.message,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})}finally{}}else a.dismiss==="cancel"&&Swal.fire({text:"Record was not deleted.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn fw-bold btn-primary"}})})})})},y=()=>{if(permissions.modDelete==0)return;const r=m.querySelectorAll('[type="checkbox"]');b=document.querySelector('[data-kt-table-toolbar="base"]'),p=document.querySelector('[data-kt-table-toolbar="selected"]'),k=document.querySelector('[data-kt-table-select="selected_count"]');const s=document.querySelector('[data-kt-table-select="delete_selected"]');r.forEach(o=>{o.addEventListener("click",function(){setTimeout(function(){g()},50)})}),s.addEventListener("click",function(){Swal.fire({text:"Are you sure you want to delete selected record(s)?",icon:"warning",showCancelButton:!0,buttonsStyling:!1,confirmButtonText:"Yes, delete!",cancelButtonText:"No, cancel",customClass:{confirmButton:"btn fw-bold btn-danger",cancelButton:"btn fw-bold btn-active-light-primary"}}).then(function(o){if(o.value){var e=[];r.forEach(n=>{var t=n.closest("tr").querySelector(".delete-btn");t&&n.checked&&e.push(t.getAttribute("data-id"))});var a=JSON.stringify(e);Swal.fire({text:"Deleting record(s)...",showConfirmButton:!1,allowEscapeKey:!1,allowOutsideClick:!1}),fetch(h+"?modAction=modDelete&modID="+permissions.modID+"&selected="+a,{method:"DELETE",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")}}).then(function(n){return n.json()}).then(function(n){if(!n.ok){Swal.fire({text:n.msg,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}Swal.fire({text:"Record(s) deleted!.",icon:"success",buttonsStyling:!1,allowOutsideClick:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn fw-bold btn-primary"}}).then(function(){c.ajax.reload(),m.querySelectorAll('[type="checkbox"]')[0].checked=!1}).then(function(){g(),y()})}).catch(function(n){n&&Swal.fire({text:n,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})})}else o.dismiss==="cancel"&&Swal.fire({text:"Selected record(s) not deleted.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn fw-bold btn-primary"}})})})},g=()=>{const r=m.querySelectorAll('tbody [type="checkbox"]');let s=!1,o=0;r.forEach(e=>{e.checked&&(s=!0,o++)}),s?(k.innerHTML=o,b.classList.add("d-none"),p.classList.remove("d-none")):(b.classList.remove("d-none"),p.classList.add("d-none"))},O=function(){if(permissions.modReport==0)return;const r=document.getElementById("kt_modal_export");if(!r)return;const s=r.querySelector("#kt_modal_export_form"),o=new bootstrap.Modal(r);var e=FormValidation.formValidation(s,{fields:{format:{validators:{notEmpty:{message:"File format is required"}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap:new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row",eleInvalidClass:"",eleValidClass:""})}});const a=r.querySelector('[data-kt-modal-action="submit"]');a.addEventListener("click",function(n){n.preventDefault(),e&&e.validate().then(function(l){if(l=="Valid"){a.setAttribute("data-kt-indicator","on");var t=document.getElementById("export-format").value;a.setAttribute("data-kt-indicator","on"),a.disabled=!0,fetch(v+"?modAction=modReport&modID="+permissions.modID,{method:"POST",headers:{Authorization:`Bearer ${apiToken}`,"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content"),"Content-Type":"application/json"}}).then(function(i){return i.json()}).then(function(i){if(!i.ok){a.removeAttribute("data-kt-indicator"),a.disabled=!1,Swal.fire({text:i.msg,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}a.removeAttribute("data-kt-indicator"),a.disabled=!1,o.hide(),Swal.fire({text:"Data is ready to be exported!",icon:"success",allowOutsideClick:!1,buttonsStyling:!1,confirmButtonText:"Ok, Export!",customClass:{confirmButton:"btn btn-primary"}}).then(function(d){t==="print"?c.button(".buttons-print").trigger():t==="excel"?c.button(".buttons-excel").trigger():t==="csv"?c.button(".buttons-csv").trigger():t==="pdf"&&c.button(".buttons-pdf").trigger()})}).catch(function(i){i&&(a.removeAttribute("data-kt-indicator"),a.disabled=!1,Swal.fire({text:i,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}))})}else Swal.fire({text:"Sorry, looks like there are some errors detected, please try again.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})})})};return{init:function(){m=document.getElementById("kt_table_common"),m&&permissions.modRead!=0&&(B(),T(),A(),y(),O())}}}();KTUtil.onDOMContentLoaded(function(){_.init()});