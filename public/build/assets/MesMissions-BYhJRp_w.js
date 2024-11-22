import{Q as g,j as p,r as f,o,d as r,b as u,u as v,w,F as _,Z as k,a as t,k as M,s as C,g as D,t as l,n as S,e as I,p as N,q as V,f as b}from"./app-CxtKqfHr.js";import{_ as L}from"./AppLayout-fEMEReFp.js";import{_ as A}from"./_plugin-vue_export-helper-DlAUqK2U.js";/* empty css            */const s=d=>(N("data-v-4b4ee681"),d=d(),V(),d),B={class:"container mx-auto p-6"},$=s(()=>t("h2",{class:"text-3xl font-semibold mb-6"},"Mes Missions",-1)),q={class:"mb-6"},z={class:"table-container overflow-auto max-h-[400px] border border-gray-300 rounded-lg shadow-lg"},E={class:"min-w-full bg-white"},F=s(()=>t("thead",{class:"sticky top-0 bg-gray-200 border-b border-gray-300"},[t("tr",null,[t("th",{class:"py-3 px-4 text-left font-medium text-gray-700"},"ID"),t("th",{class:"py-3 px-4 text-left font-medium text-gray-700"},"Nom"),t("th",{class:"py-3 px-4 text-left font-medium text-gray-700"},"Prénom"),t("th",{class:"py-3 px-4 text-left font-medium text-gray-700"},"Portable"),t("th",{class:"py-3 px-4 text-left font-medium text-gray-700"},"Actions")])],-1)),P={class:"py-3 px-4 border-b text-gray-700"},Q={class:"py-3 px-4 border-b text-gray-700"},T={class:"py-3 px-4 border-b text-gray-700"},j={class:"py-3 px-4 border-b text-gray-700"},R={class:"py-3 px-4 border-b"},U=["onClick"],Z={key:0,class:"mt-2"},G={key:0,class:"bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-md relative",role:"alert"},H=s(()=>t("strong",{class:"font-bold"},"Attention !",-1)),J=s(()=>t("br",null,null,-1)),K=s(()=>t("span",{class:"block sm:inline"},"En cours de validation",-1)),O=[H,J,K],W={key:1,class:"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative",role:"alert"},X=s(()=>t("strong",{class:"font-bold"},"Attention !",-1)),Y=s(()=>t("span",{class:"block sm:inline"},[b("Mission non démarrée "),t("br"),b(" ou à corriger")],-1)),tt=[X,Y],et={__name:"MesMissions",setup(d){const{props:h}=g(),m=p(()=>h.missions||[]),i=f(""),x=p(()=>{const a=i.value.toLowerCase();return m.value.filter(n=>{const e=String(n.nom||"").toLowerCase(),c=String(n.statut||"").toLowerCase();return e.includes(a)||c.includes(a)})}),y=a=>{window.location.href=`/missionsz/${a}`};return(a,n)=>(o(),r(_,null,[u(v(k),{title:"Mes missions"}),u(L,{title:"Dashboard"},{default:w(()=>[t("div",B,[$,t("div",q,[M(t("input",{"onUpdate:modelValue":n[0]||(n[0]=e=>i.value=e),type:"text",placeholder:"Rechercher par nom ou statut...",class:"w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"},null,512),[[C,i.value]])]),t("div",z,[t("table",E,[F,t("tbody",null,[(o(!0),r(_,null,D(x.value,e=>(o(),r("tr",{key:e.id,class:"hover:bg-gray-50"},[t("td",P,l(e.id),1),t("td",Q,l(e.nom),1),t("td",T,l(e.prenom),1),t("td",j,l(e.portable),1),t("td",R,[t("button",{onClick:c=>y(e.id),class:S(["px-4 py-2 rounded font-semibold text-white",e.raisonsocial==1?"bg-orange-500 hover:bg-orange-600":"bg-blue-500 hover:bg-blue-600"])},l(e.raisonsocial==1?"Corriger et terminer":"Détails et démarré"),11,U),e.raisonsocial!==void 0?(o(),r("div",Z,[e.raisonsocial==1?(o(),r("div",G,O)):(o(),r("div",W,tt))])):I("",!0)])]))),128))])])])])]),_:1})],64))}},nt=A(et,[["__scopeId","data-v-4b4ee681"]]);export{nt as default};