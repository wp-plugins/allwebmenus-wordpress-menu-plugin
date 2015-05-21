//----------DHTML WordPress Menu Created using AllWebMenus PRO ver 5.3-#940---------------
// This project has been compiled for (and will work under): Unlimited Domains
var awmMenuName='menu1';
var awmLibraryBuild=940;
var awmLibraryPath='/awmdata';
var awmImagesPath='/awmdata/menu1';
var awmSupported=(navigator.appName + navigator.appVersion.substring(0,1)=="Netscape5" || document.all || document.layers || navigator.userAgent.indexOf('Opera')>-1 || navigator.userAgent.indexOf('Konqueror')>-1)?1:0;
if (awmSupported){
var nua=navigator.userAgent,scriptNo=(nua.indexOf('Chrome')>-1||nua.indexOf('Safari')>-1||nua.indexOf('Gecko')>-1||nua.indexOf('Opera')>-1||nua.indexOf('Lumia')>-1||nua.indexOf('WPDesktop')>-1)?2:1;
var mpi=document.location,xt="";
var mpa=mpi.protocol+"//"+mpi.host;
var mpi=mpi.protocol+"//"+mpi.host+mpi.pathname;
if(scriptNo==1){oBC=document.all.tags("BASE");if(oBC && oBC.length) if(oBC[0].href) mpi=oBC[0].href;}
while (mpi.search(/\\/)>-1) mpi=mpi.replace("\\","/");
mpi=mpi.substring(0,mpi.lastIndexOf("/")+1);
var mpin=mpi;
var e=document.getElementsByTagName("SCRIPT");
for (var i=0;i<e.length;i++){if (e[i].src){if (e[i].src.indexOf(awmMenuName+".js")!=-1){xt=e[i].src.split("/");if (xt[xt.length-1]==awmMenuName+".js"){xt=e[i].src.substring(0,e[i].src.length-awmMenuName.length-3);if (e[i].src.indexOf("://")!=-1){mpi=xt;}else{if(xt.substring(0,1)=="/")mpi=mpa+xt; else mpi+=xt;}}}}}
while (mpi.search(/\/\.\//)>-1) {mpi=mpi.replace("/./","/");}
var awmMenuPath=mpi.substring(0,mpi.length-1);
while (awmMenuPath.search("'")>-1) {awmMenuPath=awmMenuPath.replace("'","%27");}
document.write("<SCRIPT SRC='"+(awmMenuPath+awmLibraryPath).replace(/\/$/,"")+"/awmlib"+scriptNo+".js'><\/SCRIPT>");
document.write("<SCRIPT SRC='"+(awmMenuPath+awmLibraryPath).replace(/\/$/,"")+"/awmserversidemenu.js'><\/SCRIPT>");
var n=null;
awmzindex=1000;
}

var awmImageName='';
var awmPosID='';
var awmPosClass='';
var awmSubmenusFrame='';
var awmSubmenusFrameOffset;
var awmOptimize=0;
var awmHash='NDIIHKCZCRFUSIJWUKQYEMMACOUI';
var awmNoMenuPrint=1;
function awmBuildMenu(){
its1=new ItemStyle("name=menu1_main_item_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=15px;textsize1=15px;textsize2=15px;color0=#F2F2F2;color1=#F2F2F2;color2=#F2F2F2;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#1C344C;bgcolor1=#287D7D;bgcolor2=#91C46C;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
its2=new ItemStyle("name=menu1_sub_item_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=13px;textsize1=13px;textsize2=13px;color0=#F2F2F2;color1=#F2F2F2;color2=#F2F2F2;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#1C344C;bgcolor1=#287D7D;bgcolor2=#91C46C;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
its3=new ItemStyle("name=menu1_sub_item_plus_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=13px;textsize1=13px;textsize2=13px;color0=#F2F2F2;color1=#F2F2F2;color2=#F2F2F2;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#1C344C;bgcolor1=#287D7D;bgcolor2=#91C46C;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
its4=new ItemStyle("name=menu1_main_group_header_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=15px;textsize1=15px;textsize2=15px;color0=#FFFFFF;color1=#FFFFFF;color2=#FFFFFF;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#0055E5;bgcolor1=#0055E5;bgcolor2=#0055E5;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
its5=new ItemStyle("name=menu1_main_group_footer_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=15px;textsize1=15px;textsize2=15px;color0=#FFFFFF;color1=#FFFFFF;color2=#FFFFFF;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#0055E5;bgcolor1=#0055E5;bgcolor2=#0055E5;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
its6=new ItemStyle("name=menu1_sub_group_header_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=15px;textsize1=15px;textsize2=15px;color0=#FFFFFF;color1=#FFFFFF;color2=#FFFFFF;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#0055E5;bgcolor1=#0055E5;bgcolor2=#0055E5;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
its7=new ItemStyle("name=menu1_sub_group_footer_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=15px;textsize1=15px;textsize2=15px;color0=#FFFFFF;color1=#FFFFFF;color2=#FFFFFF;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#0055E5;bgcolor1=#0055E5;bgcolor2=#0055E5;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
its8=new ItemStyle("name=menu1_sub_group_plus_header_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=15px;textsize1=15px;textsize2=15px;color0=#FFFFFF;color1=#FFFFFF;color2=#FFFFFF;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#0055E5;bgcolor1=#0055E5;bgcolor2=#0055E5;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
its9=new ItemStyle("name=menu1_sub_group_plus_footer_style;textfont0=Verdana, Arial, Helvetica, sans-serif;textfont1=Verdana, Arial, Helvetica, sans-serif;textfont2=Verdana, Arial, Helvetica, sans-serif;textsize0=15px;textsize1=15px;textsize2=15px;color0=#FFFFFF;color1=#FFFFFF;color2=#FFFFFF;padding0=6px 8px 6px 8;padding1=6px 8px 6px 8;padding2=6px 8px 6px 8;bgcolor0=#0055E5;bgcolor1=#0055E5;bgcolor2=#0055E5;align0=center;align1=center;align2=center;cursor=hand;margin0=0,2;margin1=0,2;margin2=0,2;subimage0=cross.png;subimagewidth0=9;subimage1=cross.png;subimagewidth1=9;subimage2=cross.png;subimagewidth2=9");
grs1=new GroupStyle("name=menu1_main_group_style;bgcolor=transparent;type=1;uniform=1");
grs2=new GroupStyle("name=menu1_sub_group_style;bgcolor=transparent;autoScroll=1");
grs3=new GroupStyle("name=menu1_sub_group_plus_style;bgcolor=transparent;autoScroll=1");
grsc1=CopyGroupStyle(grs1,"main_group_style");
grsc2=CopyGroupStyle(grs2,"sub_group_style");
grsc3=CopyGroupStyle(grs3,"sub_group_plus_style");
itsc1=CopyItemStyle(its1,"main_item_style");
itsc2=CopyItemStyle(its2,"sub_item_style");
itsc3=CopyItemStyle(its3,"sub_item_plus_style");
itsc4=CopyItemStyle(its4,"main_group_header_style");
itsc5=CopyItemStyle(its5,"main_group_footer_style");
itsc6=CopyItemStyle(its6,"sub_group_header_style");
itsc7=CopyItemStyle(its7,"sub_group_footer_style");
itsc8=CopyItemStyle(its8,"sub_group_plus_header_style");
itsc9=CopyItemStyle(its9,"sub_group_plus_footer_style");
wpgroup=menu1=new Menu("style=menu1_main_group_style;keynav=2;offsetx=10;offsety=10");
wplevel=0;
wphf_menu1=["",""];
}