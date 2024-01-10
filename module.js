// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * aulas_toribio_Format_renderer
 *
 * @package    aulas_toribio_Format
 * @author     aulas_toribio
 * @copyright  aulas_toribio
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
M.format_aulas_toribio = M.format_aulas_toribio || {
    ourYUI: null,
    numsections: 0,
    nuActualSection: 0
};
M.format_aulas_toribio.locationurl = function(idcourse){
    url = window.location.hash;
    if(url){
    var sec = url.substr(9);
        if(sec!=0){
        url = '';
        this.show(sec,idcourse);
        }
    }
    
    
};
M.format_aulas_toribio.Organizar = function(){
    var arModules = document.getElementsByClassName("section img-text");
    var elModule = arModules[0];
    var arActvities = elModule.getElementsByClassName("activity");
    
    elModule.style.display="flex";
    elModule.style.flexWrap="wrap";
    elModule.style.margin="0";
    elModule.style.padding="0";
    
    
    for (var i = 0; i < arActvities.length;i++){
    var elActivitie = arActvities[i];
    var elActions =null;

    var laurl = document.querySelectorAll('.activityinstance a');

    laurl.forEach((function(x){x.setAttribute("target","_blank");}));         
    elActivitie.getElementsByClassName("activityinstance")[0].className="activityinstance btn btn-secondary";
    elActivitie.getElementsByClassName("activityinstance")[0].style.marginBottom="10px";
    elActivitie.getElementsByClassName("activityinstance")[0].style.marginTop="10px";
    elActivitie.getElementsByClassName("iconlarge activityicon")[0].style.display="none";
    elActions = elActivitie.getElementsByClassName("actions")[0];   
    // if(elActions){
    // elActions.style.display="none";
    //         }
    
    }
 };
M.format_aulas_toribio.init = function (Y, numsections,idcourse) {
    this.ourYUI = Y;
    this.numsections = parseInt(numsections);
    let endSection = this.numsections + 1;
    if(document.getElementById('module-'+endSection)){
    // document.getElementById('module-'+endSection).style.display = 'none';
    }
    document.getElementById('rowinit').style.display = 'flex';
    document.getElementById('charge').style.display = 'none';
    var section = document.getElementById('aulas_toribio-container-section');
    section.setAttribute('class', 'col-sm-12');
    section.style.display = 'block';
    // document.getElementById('section-0').style.display = 'block';
    document.getElementById('aulas_toribio-modules-container').style.display = 'block';
    document.getElementById('above').style.display='none !important';

    
    // for(let i=1; i <= this.numsections; i++){
    //     var moduleElement = document.getElementById('dashboard-aulas_toribio-'+i);
    //     moduleElement.addEventListener('mouseenter', e => {
    //         document.getElementById('aulas_toribio-button-section-'+i).classList.add('aulas_toribio-after-class-'+i);

    //     });
    //     moduleElement.addEventListener('mouseleave', e => {
    //         document.getElementById('aulas_toribio-button-section-'+i).classList.remove('aulas_toribio-after-class-'+i);

    //     });
    // }
    

    this.hide();
    this.hideback();    
    // this.Organizar();
    this.locationurl(idcourse);
};
M.format_aulas_toribio.hide = function () {
    for (var i = 1; i <= this.numsections; i++) {
        if (document.getElementById('aulas_toribio-button-section-' + i) != undefined) {
            var buttonsection = document.getElementById('aulas_toribio-button-section-' + i);
            buttonsection.setAttribute('class', buttonsection.getAttribute('class').replace('sectionvisible', ''));
            document.getElementById('section-' + i).style.display = 'none';
            document.getElementById('container-'+ i).style.display='none'; 
        }
    }
};
M.format_aulas_toribio.fullhide = function () {
    this.hide();
    if (document.getElementById('aulas_toribio-button-section-' + 0) != undefined) {
        var buttonsection = document.getElementById('aulas_toribio-button-section-' + 0);
        this.hidehome();
    }
};

M.format_aulas_toribio.show = function (id, courseid) {
    this.fullhide();
    this.nuActualSection = id;
    /**
     * Apenas se muestra el modulo lleno un arreglo con el total de actividades
     */
    var activities = document.querySelectorAll('#section-'+this.nuActualSection +' .img-text li');
    /**
     * Recorre todas las actividades y las agrega al arreglo
     */
    activities.forEach(function(activity) {
        activity.style.display='none';
    });
    var buttonsection = document.getElementById('aulas_toribio-button-section-' + id);
    var currentsection = document.getElementById('section-' + id); 
    // var nonvideo = document.querySelector('#container-'+id+">#nonvideo");
    // var sectioninternal = document.getElementById('aulas_toribio-container-section');
    document.getElementById('above').style.display='none !important';
    buttonsection.setAttribute('class', buttonsection.getAttribute('class') + ' sectionvisible');
    // if (nonvideo){
    //     console.log('nonvideo hay '+ nonvideo);
    //     sectioninternal.setAttribute('class', 'col-sm-12');
    //     currentsection.setAttribute('class', 'col-sm-12');
    //     currentsection.style.marginTop = '0rem';
    // }
    // else{
    // console.log('nonvideo  no hay '+ nonvideo);
    // sectioninternal.setAttribute('class', 'col-sm-12');
    // currentsection.style.marginTop = '4rem';
    // }
    currentsection.style.display = 'block';
    
    
    currentContainer = document.getElementById('container-'+ id);
    currentContainer.style.display='block';    
    document.cookie = 'sectionvisible_' + courseid + '=' + id + '; path=/';
    M.format_aulas_toribio.h5p();
    document.getElementById('summary').style.display='block !important';
    var actmodules = document.querySelectorAll('#section-' + id + ' .activityinstance');
    var actmodulesA = document.querySelectorAll('#section-' + id + ' .activityinstance a');
    var actmodulesIMG = document.querySelectorAll('#section-' + id + ' .activityinstance img');
    var actmodulesSPAN = document.querySelectorAll('#section-' + id + ' .activityinstance span');

    document.getElementById("section-" + id).appendChild(currentContainer);
    

    // var ActmoduleT = document.querySelectorAll('#section-' + id + ' .section li>div');
    // var ActmoduleT1 = document.querySelectorAll('#section-' + id + ' .section li');
    // var imageAct = document.getElementById('module-image-balboa');
    // var textCard = document.querySelectorAll('.instancename');

    // for (let i = 0; i < textCard.length; i++) {
    //     var text = textCard[i];
    //     let innerTextName = text.innerText;
    //     if(innerTextName.length > 45){
    //         innerTextName = innerTextName.substring(0,15);
    //         text.innerText = innerTextName;
    //     } 

    // }
    
    // for (let i = 0; i < ActmoduleT.length; i++) {
        
    //     var idAct = ActmoduleT1[i].getAttribute('id');
    //     var exist = document.querySelector('#'+idAct+'>div #module-image-balboa');
    //     if(!exist){
    //         ActmoduleT[i].appendChild(imageAct.cloneNode(true));
    //     }
    //     else{
    //         exist.parentNode.removeChild(exist);
    //         ActmoduleT[i].appendChild(imageAct.cloneNode(true));
    //     }
        
    // }


    var ActmoduleFinish = document.querySelectorAll('.activity-information .btn-outline-success');

    ActmoduleFinish.forEach((function(x){
        x.innerHTML = '<i class="fa fa-check" aria-hidden="true"></i>';       
    }));
    


    actmodules.forEach((function(x){
        x.className="activityinstance activities";
    }));
    actmodulesA.forEach((function(x){
        x.classList.add('formataulas_toribioiconlink');
    }));
    actmodulesIMG.forEach((function(x){
        x.classList.add('formataulas_toribioiconimage');
    }));
    actmodulesSPAN.forEach((function(x){
        x.classList.add('formataulas_toribioiconspan');
    }));
    let containerPageToScroll = document.getElementById('page');
    containerPageToScroll.style.overflow='initial';
    this.hidehome();
    this.showback(id);
    window.scrollTo({ top: 0, behavior: 'smooth' });
};


M.format_aulas_toribio.showmodule= function(){
    
    document.getElementById('charge').style.display='none';
    document.getElementById('above').style.display='none !important';
    document.getElementById('rowinit').style.display='block';
    document.getElementById('summary').style.display='block';
    
    document.getElementsByClassName(course-content)[0].style.display="block";
    for (var i = 1; i <= this.numsections; i++) {
    var currentsection = document.getElementById('section-' + i).classList.remove("col-sm-6");
    var currentsection = document.getElementById('section-' + i).classList.add("col-sm-12");
    var currentsection = document.getElementById('section-' + i).style.display='block';
    currentsection.setAttribute('class', 'col-sm-12');
    }
}
M.format_aulas_toribio.hidehome = function () {
    document.getElementById('section-' + 0).style.display = 'none';
    document.getElementById('aulas_toribio-modules-container').style.display = 'none';
}
M.format_aulas_toribio.hideback = function () {
    for (var i = 1; i <= this.numsections; i++) {
        document.getElementById('aulas_toribio-back-nav-'+i).style.display = 'none';
        document.getElementById('container-'+ i).style.display= 'none';    
    }
}
M.format_aulas_toribio.showback = function (id) {
    if(this.nuActualSection != id){
    document.getElementById('aulas_toribio-back-nav-'+id).style.display = 'none';
    }else{
    document.getElementById('aulas_toribio-back-nav-'+id).style.display = 'block';
    // document.getElementById('aulas_toribio-back-nav-'+id).style.marginBottom = '3rem';
    document.getElementById('aulas_toribio-back-nav-'+id).style.padding = '.5rem 0rem';
    }
}
M.format_aulas_toribio.back = function (courseid) {
    this.hide();
    document.getElementById('section-' + 0).style.display = 'block';
    document.getElementById('aulas_toribio-modules-container').style.display = 'block';
    var sectioninternal = document.getElementById('aulas_toribio-container-section');
    sectioninternal.setAttribute('class', 'col-sm-12');
    this.hideback();
};
M.format_aulas_toribio.h5p = function () {
    window.h5pResizerInitialized = false;
    var iframes = document.getElementsByTagName('iframe');
    var ready = {
        context: 'h5p',
        action: 'ready'
    };
    for (var i = 0; i < iframes.length; i++) {
        if (iframes[i].src.indexOf('h5p') !== -1) {
            iframes[i].contentWindow.postMessage(ready, '*');
        }
    }
};

/**
 * 
 * @param {*} section 
 * @param {*} momento 
 * @param {*} numactividades 
 */
var cahngenames = false;
var arr;
M.format_aulas_toribio.showmoment =  function(section,momento){

    /**
     * Selecciona como activo el momento actual
     */
    let cardMoments = document.querySelectorAll('.card-moment-content .card-moment-image');
    let activemoment = 0;

    for (let i = 0; i<= cardMoments.length; i++){
        if(section == 2){
            activemoment=5;
        }
        else if(section == 3){
            activemoment=10;
        }else{
            // console.log(cardMoments[i]);
        }

    }


        /**
         * Si la card esta seleccionada agrego la imagen
         */
        

    /**
     * mostrar el div de la descripcion del momento
     */
    let m = '';
    switch(momento){
        case 1: 
            m='empatizar';
            break;
        case 2: 
            m='definir';
            break;
        case 3: 
            m='idear';
            break;
        case 4: 
            m='prototipar';
            break;
        case 5: 
            m='testear';
            break;
        default: '';
    }
    let initialmoment = 0;
    if(section == 2){
        initialmoment=5;
    }
    if(section == 3){
        initialmoment=10;
    }
    let section_active= document.querySelector("[section=active]");
    let arrow_active= document.querySelector("[arrow=active]");
    let card_active= document.querySelector("[card=active]");
    if(section_active){
        arrow_active.style.display = 'none';
        section_active.style.display='none';
        section_active.setAttribute('section','disable');
        arrow_active.setAttribute('arrow','disable');
        card_active.setAttribute('card','disable');
    } 


    let showmoment = initialmoment + momento;
    let section_momento = document.getElementById('momento'+showmoment);
    let arrow_momento = document.querySelector('#section-'+section+' #arrow-image-'+m);
    let card_momento = document.querySelector('#section-'+section+' #card-'+m);
    let heightPage = document.body.scrollHeight;
    arrow_momento.style.display='block';
    section_momento.style.display='block';
    card_momento.classList.add('card-momento-ative-'+m);
    section_momento.setAttribute('section','active');
    arrow_momento.setAttribute('arrow','active');
    card_momento.setAttribute('card','active');
    if(heightPage > 2000){
        window.scrollTo({ top: heightPage - 300, behavior: 'smooth' });
    }else{
        window.scrollTo({ top: heightPage - 600, behavior: 'smooth' });
    }
    
    /**
     * mostrar las actividades del momento
     */
    var activities = document.querySelectorAll('#section-'+section+' .img-text li .activity-item');
    /**
     * Recorre todas las actividades y las agrega al arreglo
     */
    activities.forEach(function(activity) {
        let nombre = activity.getAttribute('data-activityname'); 
        let nombreToShow = activity.getAttribute('data-activityname');
        if(momento == 1){
            nombre = nombre.substring(1,10);
            nombreToShow =  nombreToShow.slice(11);
        }else if(momento == 2){
            nombre = nombre.substring(1,8);
            nombreToShow =  nombreToShow.slice(9);
        }
        else if(momento == 3){
            nombre = nombre.substring(1,6);
            nombreToShow =  nombreToShow.slice(7);
        }
        else if(momento == 4){
            nombre = nombre.substring(1,11);
            nombreToShow =  nombreToShow.slice(12);
        }
        else{
            nombre = nombre.substring(1,8);
            nombreToShow =  nombreToShow.slice(9);
        }
        /**
         * Muestra las actividades de cada momento
         */
        
        if(nombre.toLowerCase() == m){
            activity.parentNode.style.display='block';
        }else{
            activity.parentNode.style.display='none';
        }
        
    });

    /**
     * Quita el nombre de referencia de las actividades
     */
    if(!this.cahngenames){
        this.changenames();
        this.cahngenames = true;
    }
    
    

}

M.format_aulas_toribio.changenames = function(){
    var activitiesTitle = document.querySelectorAll("#section-1 .img-text li .activity-item .instancename");
    
    activitiesTitle.forEach(function(title) {
        if(title.innerHTML.substring(1,10).toLowerCase()=='empatizar'){
            const tToShow = title.innerHTML.slice(11);
            title.innerHTML = tToShow;
        }else if(title.innerHTML.substring(1,8).toLowerCase()== 'definir'){
            const tToShow = title.innerHTML.slice(9);
            title.innerHTML = tToShow;
        }
        else if(title.innerHTML.substring(1,6).toLowerCase()== 'idear'){
            const tToShow = title.innerHTML.slice(7);
            title.innerHTML = tToShow;
        }
        else if(title.innerHTML.substring(1,11).toLowerCase()== 'prototipar'){
            const tToShow = title.innerHTML.slice(12);
            title.innerHTML = tToShow;
        }
        else{
            const tToShow = title.innerHTML.slice(9);
            title.innerHTML = tToShow;
        }
    });
    
}


M.format_aulas_toribio.Coursecontent = function($url){
    window.open(""+$url+"");
};
M.format_aulas_toribio.Courseprocess = function($url){
    window.open(""+$url+"");
};
M.format_aulas_toribio.Forum = function($url){
    window.open(""+$url+"");
};
M.format_aulas_toribio.Meeting = function($url){
    window.open(""+$url+"");
};
M.format_aulas_toribio.Doubts = function($url){
    window.open(""+$url+"");
};