var v=function(){var i,t,u,l=function(d){t.addEventListener("click",function(r){r.preventDefault();var o=!0,n="",a=[].slice.call(i.querySelectorAll(".token-input"));if(a.map(function(e){(e.value===""||e.value.length===0)&&(o=!1)}),o===!0){a.map(function(e){n+=e.value}),t.setAttribute("data-kt-indicator","on"),t.disabled=!0;var s=new FormData(i);s.append("token",n),fetch(`${APP_URL}/auth/2fa/verify`,{method:"POST",body:s}).then(function(e){return e.json()}).then(function(e){if(!e.ok){t.removeAttribute("data-kt-indicator"),t.disabled=!1,Swal.fire({text:e.msg,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}t.removeAttribute("data-kt-indicator"),t.disabled=!1,Swal.fire({text:"You have been successfully verified!",icon:"success",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}).then(function(m){if(m.isConfirmed){a.map(function(b){b.value=""});var c=i.getAttribute("data-kt-redirect-url");c&&window.location.replace(c)}})}).catch(function(e){e&&(t.removeAttribute("data-kt-indicator"),t.disabled=!1,Swal.fire({text:e,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}))})}else swal.fire({text:"Please enter valid security code and try again.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn fw-bold btn-light-primary"}}).then(function(){KTUtil.scrollTop()})})},f=function(d){u.addEventListener("click",function(r){r.preventDefault(),t.setAttribute("data-kt-indicator","on"),t.disabled=!0;var o=new FormData(i);fetch(`${APP_URL}/auth/2fa/resend`,{method:"POST",body:o}).then(function(n){return n.json()}).then(function(n){if(!n.ok){t.removeAttribute("data-kt-indicator"),t.disabled=!1,Swal.fire({text:n.msg,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});return}t.removeAttribute("data-kt-indicator"),t.disabled=!1,Swal.fire({text:"Two Factor Authentication code resent!",icon:"success",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}})}).catch(function(n){n&&(t.removeAttribute("data-kt-indicator"),t.disabled=!1,Swal.fire({text:n,icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}))})})};return{init:function(){i=document.querySelector("#kt_sing_in_two_steps_form"),t=document.querySelector("#kt_sing_in_two_steps_submit"),u=document.querySelector("#kt_resend_button"),l(),f()}}}();KTUtil.onDOMContentLoaded(function(){v.init()});