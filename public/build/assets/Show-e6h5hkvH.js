import{_ as i}from"./AppLayout-4BVVkt-y.js";import o from"./DeleteTeamForm-nSyEqzPP.js";import{S as r}from"./SectionBorder-BQdrA2mN.js";import l from"./TeamMemberManager-D3oKabZF.js";import n from"./UpdateTeamNameForm-Cu9jzFX0.js";import{o as m,c,w as s,a,b as t,d as p,F as d,e as f}from"./app-DRiAhqHX.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./Modal-Dtlkj-bL.js";import"./SectionTitle-BzOZ1avw.js";import"./ConfirmationModal-BBik0FAm.js";import"./DangerButton-CJFiRO-w.js";import"./SecondaryButton-OU5Z850W.js";import"./ActionMessage-Bos6l9yp.js";import"./DialogModal-CRpfLMw-.js";import"./FormSection-B5lm4w9d.js";import"./TextInput-BBnRMVb_.js";import"./InputLabel-CMxJSxoy.js";import"./PrimaryButton-C6TdmZZX.js";/* empty css            */const u=a("h2",{class:"font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"}," Team Settings ",-1),x={class:"max-w-7xl mx-auto py-10 sm:px-6 lg:px-8"},E={__name:"Show",props:{team:Object,availableRoles:Array,permissions:Object},setup(e){return(b,g)=>(m(),c(i,{title:"Team Settings"},{header:s(()=>[u]),default:s(()=>[a("div",null,[a("div",x,[t(n,{team:e.team,permissions:e.permissions},null,8,["team","permissions"]),t(l,{class:"mt-10 sm:mt-0",team:e.team,"available-roles":e.availableRoles,"user-permissions":e.permissions},null,8,["team","available-roles","user-permissions"]),e.permissions.canDeleteTeam&&!e.team.personal_team?(m(),p(d,{key:0},[t(r),t(o,{class:"mt-10 sm:mt-0",team:e.team},null,8,["team"])],64)):f("",!0)])])]),_:1}))}};export{E as default};