__NUXT_JSONP__("/generating-console-tasker", (function(a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,_,$,aa,ab,ac,ad,ae,af,ag,ah,ai,aj,ak,al,am,an,ao,ap,aq,ar,as,at,au,av,aw,ax,ay,az,aA,aB,aC,aD,aE,aF,aG,aH){return {data:[{document:{slug:"generating-console-tasker",description:"How to generate a Console Tasker via Artisan.",title:"Generating console tasker",position:100,category:"The basics",implementations:["generic task: the default implementation, it can execute any logic","file creator: generates a new file, great for automatic code generation","files editor: updates existing files, also great for code auto-generation"],toc:[{id:ak,depth:X,text:al},{id:am,depth:X,text:an},{id:ao,depth:X,text:ap}],body:{type:"root",children:[{type:b,tag:j,props:{},children:[{type:a,value:"By "},{type:b,tag:Y,props:{},children:[{type:a,value:"console tasker"}]},{type:a,value:" we mean an Artisan command running one or more tasks. This package provides a command to generate console taskers and related tasks."}]},{type:a,value:f},{type:b,tag:Z,props:{id:ak},children:[{type:b,tag:K,props:{href:"#generating-the-command",ariaHidden:_,tabIndex:$},children:[{type:b,tag:c,props:{className:[aa,ab]},children:[]}]},{type:a,value:al}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"To generate only the Artisan command, without any task, we can run:"}]},{type:a,value:f},{type:b,tag:u,props:{className:[v]},children:[{type:b,tag:w,props:{className:[x,O]},children:[{type:b,tag:n,props:{},children:[{type:a,value:"php artisan make:console-tasker MyCommand\n"}]}]}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"that will produce the following output:"}]},{type:a,value:f},{type:b,tag:aq,props:{src:"make_console_tasker.png"},children:[]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"As we can note, the command was successfully created while the generatation of tasks was skipped, since we didn't specify any. The output also shows the command that was created and reminds us to update it with signature, description and a list of tasks to run."}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"Now the newly generated command looks like this:"}]},{type:a,value:f},{type:b,tag:u,props:{className:[v]},children:[{type:b,tag:w,props:{className:[x,P]},children:[{type:b,tag:n,props:{},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:Q}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:L},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:D},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:"Commands"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:y},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:z}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:L},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:D},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:A},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:M}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:"as"}]},{type:a,value:" Tasks"},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:z}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:R},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:S},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:"Concerns"},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:ar}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:z}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:"Illuminate"},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:D},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:as}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:y},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:T}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,U,m]},children:[{type:a,value:M}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:V}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:a,value:as}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:B}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:z}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:ar}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:ac},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:"\u002F**\n     * The name and signature of the console command.\n     *\n     * "},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:ad}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:q}]}]},{type:a,value:E}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:F}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,ae]},children:[{type:a,value:"$signature"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:N}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,q,G]},children:[{type:a,value:"'command:name'"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:ac},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:"\u002F**\n     * The console command description.\n     *\n     * "},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:ad}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:q}]}]},{type:a,value:E}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:F}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,ae]},children:[{type:a,value:"$description"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:N}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,q,G]},children:[{type:a,value:"'Command description'"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:ac},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:"\u002F**\n     * The tasks to run.\n     *\n     * "},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:ad}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:q}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:at}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:au}]}]},{type:a,value:E}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:F}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h,"type-declaration"]},children:[{type:a,value:"array"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,ae]},children:[{type:a,value:"$tasks"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:N}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:at}]},{type:a,value:W},{type:b,tag:c,props:{className:[d,p]},children:[{type:a,value:"\u002F\u002F @todo list tasks to run"}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:au}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:C}]},{type:a,value:f}]}]}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"We can also define the signature of a command when we generate it:"}]},{type:a,value:f},{type:b,tag:u,props:{className:[v]},children:[{type:b,tag:w,props:{className:[x,O]},children:[{type:b,tag:n,props:{},children:[{type:a,value:"php artisan make:console-tasker MyCommand --command"},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:N}]},{type:a,value:"my-command\n\nphp artisan make:console-tasker MyCommand -c my-command\n"}]}]}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"By doing so, the signature of the command is set to "},{type:b,tag:n,props:{},children:[{type:a,value:"my-command"}]},{type:a,value:" and the reminder changes to "},{type:b,tag:af,props:{},children:[{type:a,value:"define command description and list tasks to run"}]},{type:a,value:av}]},{type:a,value:f},{type:b,tag:Z,props:{id:am},children:[{type:b,tag:K,props:{href:"#generating-the-tasks",ariaHidden:_,tabIndex:$},children:[{type:b,tag:c,props:{className:[aa,ab]},children:[]}]},{type:a,value:an}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"Generating tasks is possible by simply providing the the "},{type:b,tag:n,props:{},children:[{type:a,value:"--tasks"}]},{type:a,value:" (or "},{type:b,tag:n,props:{},children:[{type:a,value:"-t"}]},{type:a,value:") option:"}]},{type:a,value:f},{type:b,tag:u,props:{className:[v]},children:[{type:b,tag:w,props:{className:[x,O]},children:[{type:b,tag:n,props:{},children:[{type:a,value:"php artisan make:console-tasker MyCommand --tasks"},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:N}]},{type:a,value:"FirstTask,SecondTask\n\nphp artisan make:console-tasker MyCommand -t FirstTask,SecondTask\n"}]}]}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"As shown above, multiple tasks can be specified by separating them with a comma."}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"The output now shows also the generated tasks and suggests us to implement them:"}]},{type:a,value:f},{type:b,tag:aq,props:{src:"make_console_tasker_tasks.png"},children:[]},{type:a,value:f},{type:b,tag:aw,props:{},children:[{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"Tasks are generated in "},{type:b,tag:n,props:{},children:[{type:a,value:"app\u002FConsole\u002FTasks"}]},{type:a,value:" by default, but we can also "},{type:b,tag:K,props:{href:"installation#tasks_directory"},children:[{type:a,value:"choose a different directory"}]},{type:a,value:av}]},{type:a,value:f}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"Out of the box Console Tasker provides 3 different task implementations:"}]},{type:a,value:f},{type:b,tag:"list",props:{":items":"implementations"},children:[{type:a,value:f}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"If we want to generate a file creator or a files editor, we can append modifiers when defining the tasks to generate:"}]},{type:a,value:f},{type:b,tag:u,props:{className:[v]},children:[{type:b,tag:w,props:{className:[x,O]},children:[{type:b,tag:n,props:{},children:[{type:a,value:"php artisan make:console-tasker MyCommand -t CreateController:c,AddRelationToUser:e\n"}]}]}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"Modifiers are appended to task names with a column. By default a file creator can be generated with the modifier "},{type:b,tag:af,props:{},children:[{type:a,value:"c"}]},{type:a,value:" and a files editor with the modifier "},{type:b,tag:af,props:{},children:[{type:a,value:"e"}]}]},{type:a,value:f},{type:b,tag:aw,props:{},children:[{type:a,value:f},{type:b,tag:j,props:{},children:[{type:b,tag:K,props:{href:"installation#modifiers"},children:[{type:a,value:"Custom modifiers and implementations"}]},{type:a,value:" can be added to the configuration of Console Tasker."}]},{type:a,value:f}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"A variant to modifiers is using common verbs to"}]},{type:a,value:f},{type:b,tag:j,props:{},children:[{type:a,value:"This is how the 3 task implementations look like:"}]},{type:a,value:f},{type:b,tag:"code-group",props:{},children:[{type:a,value:H},{type:b,tag:ag,props:{label:"generic task",active:""},children:[{type:a,value:f},{type:b,tag:u,props:{className:[v]},children:[{type:b,tag:w,props:{className:[x,P]},children:[{type:b,tag:n,props:{},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:Q}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:L},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:D},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:A},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:M}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:y},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:z}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:R},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:S},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:A},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:ax}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:y},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:"\u002F**\n * The task to notify admin.\n *\n *\u002F"}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:T}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,U,m]},children:[{type:a,value:"NotifyAdmin"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:V}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:a,value:ax}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:B}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:ay},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:ah}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:az}]}]},{type:a,value:E}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:F}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:t}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,ai,t]},children:[{type:a,value:aA}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:I}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:J}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:B}]},{type:a,value:W},{type:b,tag:c,props:{className:[d,p]},children:[{type:a,value:"\u002F\u002F"}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:C}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:C}]},{type:a,value:f}]}]}]},{type:a,value:H}]},{type:a,value:H},{type:b,tag:ag,props:{label:"file creator"},children:[{type:a,value:f},{type:b,tag:u,props:{className:[v]},children:[{type:b,tag:w,props:{className:[x,P]},children:[{type:b,tag:n,props:{},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:Q}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:L},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:D},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:A},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:M}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:y},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:z}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:R},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:S},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:A},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:aB}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:y},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:"\u002F**\n * The task to create controller.\n *\n *\u002F"}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:T}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,U,m]},children:[{type:a,value:"CreateController"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:V}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:a,value:aB}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:B}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:"\u002F**\n     * Retrieve the fully qualified name of the file to create\n     *\n     * "},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:ah}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:q}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:"|"}]},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:aC}]}]},{type:a,value:E}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:F}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:t}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,ai,t]},children:[{type:a,value:"getFullyQualifiedName"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:I}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:J}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:":"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:"?"}]},{type:b,tag:c,props:{className:[d,h,"return-type"]},children:[{type:a,value:q}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:B}]},{type:a,value:W},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:"return"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,"constant"]},children:[{type:a,value:aC}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:C}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:C}]},{type:a,value:f}]}]}]},{type:a,value:H}]},{type:a,value:H},{type:b,tag:ag,props:{label:"files editor"},children:[{type:a,value:f},{type:b,tag:u,props:{className:[v]},children:[{type:b,tag:w,props:{className:[x,P]},children:[{type:b,tag:n,props:{},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:Q}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:L},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:D},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:A},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:M}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:y},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:z}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,o]},children:[{type:a,value:R},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:S},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:A},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:i}]},{type:a,value:aD}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:y},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:"\u002F**\n * The task to add relation to user.\n *\n *\u002F"}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:T}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,U,m]},children:[{type:a,value:"AddRelationToUser"}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:V}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:a,value:aD}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:B}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,r,p]},children:[{type:a,value:ay},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:ah}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,m]},children:[{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:az}]}]},{type:a,value:E}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:F}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,h]},children:[{type:a,value:t}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,ai,t]},children:[{type:a,value:aA}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:I}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:J}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:B}]},{type:a,value:W},{type:b,tag:c,props:{className:[d,"this",h]},children:[{type:a,value:"$this"}]},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:aj}]},{type:b,tag:c,props:{className:[d,t]},children:[{type:a,value:"file"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:I}]},{type:b,tag:c,props:{className:[d,q,G]},children:[{type:a,value:"'path\u002Fto\u002Ffile\u002Fto\u002Fupdate'"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:J}]},{type:a,value:aE},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:aj}]},{type:b,tag:c,props:{className:[d,t]},children:[{type:a,value:"addLineAfterLast"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:I}]},{type:b,tag:c,props:{className:[d,q,G]},children:[{type:a,value:"'text to search'"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:","}]},{type:a,value:g},{type:b,tag:c,props:{className:[d,q,G]},children:[{type:a,value:"'line to add'"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:J}]},{type:a,value:aE},{type:b,tag:c,props:{className:[d,s]},children:[{type:a,value:aj}]},{type:b,tag:c,props:{className:[d,t]},children:[{type:a,value:"needsManualUpdateTo"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:I}]},{type:b,tag:c,props:{className:[d,q,G]},children:[{type:a,value:"'implement something'"}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:J}]},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:l}]},{type:a,value:k},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:C}]},{type:a,value:f},{type:b,tag:c,props:{className:[d,e]},children:[{type:a,value:C}]},{type:a,value:f}]}]}]},{type:a,value:H}]},{type:a,value:f}]},{type:a,value:f},{type:b,tag:"ul",props:{},children:[{type:a,value:f},{type:b,tag:aF,props:{},children:[{type:b,tag:Y,props:{},children:[{type:a,value:"verbs + config link"}]},{type:a,value:aG}]},{type:a,value:f},{type:b,tag:aF,props:{},children:[{type:b,tag:Y,props:{},children:[{type:a,value:"asterisk in task names to generate stubs"}]},{type:a,value:aG}]},{type:a,value:f}]},{type:a,value:f},{type:b,tag:Z,props:{id:ao},children:[{type:b,tag:K,props:{href:"#customizing-the-stubs",ariaHidden:_,tabIndex:$},children:[{type:b,tag:c,props:{className:[aa,ab]},children:[]}]},{type:a,value:ap}]}]},dir:"\u002Fen",path:"\u002Fen\u002Fgenerating-console-tasker",extension:".md",createdAt:aH,updatedAt:aH,to:"\u002Fgenerating-console-tasker"},prev:{title:"Installation",path:"\u002Fen\u002Finstallation",to:"\u002Finstallation"},next:{title:"Developing a command",path:"\u002Fen\u002Fdeveloping-command",to:"\u002Fdeveloping-command"}}],fetch:{},mutations:[]}}("text","element","span","token","punctuation","\n"," ","keyword","\\","p","\n    ",";","class-name","code","package","comment","string","doc-comment","operator","function","div","nuxt-content-highlight","pre","line-numbers","\n\n","use","Tasks","{","}","Console","\n     *\u002F","protected","single-quoted-string","\n  ","(",")","a","App","MyCommand","=","language-bash","language-php","namespace","Cerbero","ConsoleTasker","class","class-name-definition","extends","\n        ",2,"strong","h2","true",-1,"icon","icon-link","\n\n    ","@var","variable","badge","code-block","@return","function-definition","-\u003E","generating-the-command","Generating the command","generating-the-tasks","Generating the tasks","customizing-the-stubs","Customizing the stubs","img","RunsTasks","Command","[","]",".","alert","Task","\u002F**\n     * Run the task\n     *\n     * ","mixed","run","FileCreator","null","FilesEditor","\n            ","li"," \u002F\u002F\u002F\u002F\u002F\u002F\u002F\u002F\u002F\u002F\u002F\u002F\u002F\u002F","2023-06-03T15:21:56.171Z")));