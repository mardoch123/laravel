import{T as d,o as _,c as u,w as s,f as m,a as e,b as t,t as i,u as o,n as f}from"./app-Pst8fObY.js";import{_ as h}from"./FormSection-C9InrCvZ.js";import{_ as g,a as v}from"./TextInput-DU5z5aPO.js";import{_ as n}from"./InputLabel-hAv3fNkv.js";import{_ as $}from"./PrimaryButton-B2TZqOkW.js";/* empty css            */import"./SectionTitle-BkjCY_Pe.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const w={class:"col-span-6"},T={class:"flex items-center mt-2"},b=["src","alt"],x={class:"ms-4 leading-tight"},y={class:"text-gray-900 dark:text-white"},V={class:"text-sm text-gray-700 dark:text-gray-300"},k={class:"col-span-6 sm:col-span-4"},U={__name:"CreateTeamForm",setup(C){const a=d({name:""}),c=()=>{a.post(route("teams.store"),{errorBag:"createTeam",preserveScroll:!0})};return(r,l)=>(_(),u(h,{onSubmitted:c},{title:s(()=>[m(" Team Details ")]),description:s(()=>[m(" Create a new team to collaborate with others on projects. ")]),form:s(()=>[e("div",w,[t(n,{value:"Team Owner"}),e("div",T,[e("img",{class:"object-cover w-12 h-12 rounded-full",src:r.$page.props.auth.user.profile_photo_url,alt:r.$page.props.auth.user.name},null,8,b),e("div",x,[e("div",y,i(r.$page.props.auth.user.name),1),e("div",V,i(r.$page.props.auth.user.email),1)])])]),e("div",k,[t(n,{for:"name",value:"Team Name"}),t(g,{id:"name",modelValue:o(a).name,"onUpdate:modelValue":l[0]||(l[0]=p=>o(a).name=p),type:"text",class:"block w-full mt-1",autofocus:""},null,8,["modelValue"]),t(v,{message:o(a).errors.name,class:"mt-2"},null,8,["message"])])]),actions:s(()=>[t($,{class:f({"opacity-25":o(a).processing}),disabled:o(a).processing},{default:s(()=>[m(" Create ")]),_:1},8,["class","disabled"])]),_:1}))}};export{U as default};