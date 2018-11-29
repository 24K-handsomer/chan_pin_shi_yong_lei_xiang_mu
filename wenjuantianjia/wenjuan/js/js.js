window.onload = function(){
	var ul = document.getElementById("ul")
	var con = document.getElementById("con")
	var Is = document.getElementsByTagName("li");
	function getClassname(id,className){
		if(document.getElementsByClassName){
			if(id){
				var eleId = document.getElementById(id);
				return  eleId.getElementsByClassName(className);
			}else{
			return  document.getElementsByClassName(className);
			}
		}else{
			if(id){
				var eleId = document.getElementById(id);
				var na = eleId.getElementsByTagName("*");
			}else{
				var na = document.getElementsByTagName("*");
			}
			var arr=[];
			for(var i=0;i<na.length;i++){
				var txtArr = an[i].className.split(" ");
            	for(var i=0;i<txtArr.length;i++){
                	arr.push(an[i]);
            	}
			}
				return arr			
		}
	}
	/*背景图*/
	for(var i=0;i<Is.length;i++){
		Is[i].style.backgroundPosition = "2px "+(-i*33)+"px";
	}
	/*添加 替换 标题选项*/
	var wc = getClassname("con","wancheng")
	var con001 = getClassname("con","con001")
	var tj = getClassname("con","tianjia")
	var tj01 = getClassname("con","tianjia01")
	var sc = getClassname("con","shanchu")
	var sc01 = getClassname("con","shanchu01")
	var xx = getClassname("con","xuanxiang01")
	var xx01 = getClassname("con","xuanxiang001")
	var oText = getClassname("con","text1")
	var oText001 = getClassname("con","text001")
	
	var box01 = document.createElement("div");
	var oBox01 = document.createElement("div");
	var box02 = document.createElement("div");
	var oBox02 = document.createElement("div");

	Is[0].onclick = function(){
		var box = document.createElement("div");
		Is[0].parentNode.parentNode.parentNode.appendChild(box);
		var div = document.createElement("div");
		div.innerHTML =`<div class="con01">
							<h6><span class="red">*</span><span class="tit">标题</span><span class="fb"></span></h6>
							<div class="text1">
							
							</div>
							<div class="con001">
								<div class="text01">
									<textarea name="text" placeholder="标题" class="tit1" cols="30" rows="10"></textarea>
									<p>
										<input type="radio" name="feixuan" class="feixuan" id="" value="" />必选
									</p>
								</div>
								<p class="wenzi">选项文字</p>
								<div class="xuanxiang">
									<div class="xuanxiang01">
									
									</div>
									<p class="btn"><span class="tianjia">+添加选项</span> <span class="shanchu">-删除选项</span></p>
								</div>
								<input type="button" name="wc" value="完成" class="wancheng"/>
							</div>
							<div class="bianji"><span class="bianji01">编辑</span><span class="bianji02">删除</span></div>
						</div>`;
		box.appendChild(div);
		var box01 = document.createElement("div");
		var oBox01 = document.createElement("div");
		for(var i=0;i<tj.length;i++){
			tj[i].index = i
			tj[i].onclick = function tianjia(){
//				var box01 = document.createElement("div");
//				var oBox01 = document.createElement("div");
				xx[this.index].appendChild(box01);
				var div01 = document.createElement("div");
				div01.innerHTML = '<p class="list"><input type="text" class="tt" placeholder="选项" name="" value="" /></p>'
				box01.appendChild(div01);
				oText[this.index].appendChild(oBox01);
				var oDiv01 = document.createElement("div");
				oDiv01.innerHTML = '<p><input type="radio" value="" name="gender"><span class="tc">选项</span></p>'
				oBox01.appendChild(oDiv01);
			}
		}
	//	删除单选
		for(var i=0;i<sc.length;i++){
			sc[i].index = i
			sc[i].onclick = function shanchu(){ 
				if(box01.childNodes.length>=1){
					box01.removeChild(box01.childNodes[0]);
					oBox01.removeChild(oBox01.childNodes[0]);
				}
			}
		}
		//	删除完成
		for(var i=0;i<sc01.length;i++){
			sc01[i].index = i
			sc01[i].onclick = function shanchu(){
				if(box02.childNodes.length>=1){
					box02.removeChild(box02.childNodes[0]);
					oBox02.removeChild(oBox02.childNodes[0]);
				}
			}
		}
		//完成
		for(var i=0;i<wc.length;i++){
			wc[i].index = i
			wc[i].onclick = function none(){
				con001[this.index].style.display = "none"
				var tit = getClassname("con","tit")
				var tit1 = getClassname("con","tit1")
				tit[this.index].innerHTML = tit1[this.index].value
				var otc = getClassname("con","tc")
				var ott = getClassname("con","tt")
				for(var j=0;j<otc.length;j++){
					otc[j].index = j
					otc[j].innerHTML = ott[j].value;
				}
				var fx = getClassname("con","feixuan")[this.index]
				var fb = getClassname("con","fb")[this.index]
				if(fx.checked == true){
					fb.innerHTML = "(必选)"
				}else{
					fb.innerHTML = "(非选)"
				}
			}
		}
			/*鼠标以上添加边框*/
		var con01 = getClassname("con","con01")
		var con001 = getClassname("con","con001")
		var bj = getClassname("con","bianji")
		var bj01 = getClassname("con","bianji01")
		var bj02 = getClassname("con","bianji02")
		for(var i=0;i<con01.length;i++){
			con01[i].index = i;
			
			con01[i].onmouseover = function(){
				if(con001[this.index].style.display=="none"){
					con01[this.index].classList.add("border")
					bj[this.index].style.display = "block"
					
				}
			}
		}
		for(var i=0;i<bj01.length;i++){
			bj01[i].index = i;
			bj01[i].onclick = function(){
				con001[this.index].style.display="block"
				con01[this.index].classList.remove("border")
				bj[this.index].style.display = "none"
			}
			bj02[i].onclick = function(){
				con.removeChild(this.parentNode.parentNode)
			}
		}
		for(var i=0;i<con01.length;i++){
			con01[i].index = i;
			con01[i].onmouseout = function(){
				if(con001[this.index].style.display=="none"){
					con01[this.index].classList.remove("border")
					bj[this.index].style.display = "none"
					
				}
			}
		}
	}
	Is[1].onclick = function(){
		var box = document.createElement("div");
		Is[1].parentNode.parentNode.parentNode.appendChild(box);
		var div = document.createElement("div");
		div.innerHTML =`<div class="con01">
							<h6><span class="red">*</span><span class="tit">duoxuna</span><span class="blue">【多选题】</span><span class="fb"></span></h6>
							<div class="text001">
							
							</div>
							<div class="con001">
								<div class="text01">
									<textarea name="text" id="" placeholder="标题" class="tit1" cols="30" rows="10" value=""></textarea>
									<p>
										<input type="radio" name="feixuan" class="feixuan" id="" value="" />必选
									</p>
								</div>
								<p class="wenzi">选项文字</p>
								<div class="xuanxiang">
									<div class="xuanxiang001">
									
									</div>
									<p class="btn"><span class="tianjia01">+添加选项</span> <span class="shanchu01">-删除选项</span></p>
								</div>
								<input type="button" name="wc" value="完成" class="wancheng"/>
							</div>
							<div class="bianji"><span class="bianji01">编辑</span><span class="bianji02">删除</span></div>
						</div>`;
		box.appendChild(div);
		//	添加2多选
		var box02 = document.createElement("div");
		var oBox02 = document.createElement("div");
		for(var i=0;i<tj01.length;i++){
			tj01[i].index = i
			tj01[i].onclick = function tianjia01(){
				xx01[this.index].appendChild(box02);
				var div02 = document.createElement("div");
				div02.innerHTML = '<p class="list"><input type="text" class="tt" placeholder="选项" name="" id="" value="" /></p>'
				box02.appendChild(div02);
				oText001[this.index].appendChild(oBox02);
				var oDiv02 = document.createElement("div");
				oDiv02.innerHTML = '<p><input type="checkbox" value="" name="gender"><span class="tc">选项</span></p>'
				oBox02.appendChild(oDiv02);
			}
		}
		//	删除多选
		for(var i=0;i<sc01.length;i++){
			sc01[i].index = i
			sc01[i].onclick = function shanchu(){
				if(box02.childNodes.length>=1){
					box02.removeChild(box02.childNodes[0]);
					oBox02.removeChild(oBox02.childNodes[0]);
				}
			}
		}
	
		//	完成
		for(var i=0;i<wc.length;i++){
			wc[i].index = i
			wc[i].onclick = function none(){
				con001[this.index].style.display = "none"
				var tit = getClassname("con","tit")
				var tit1 = getClassname("con","tit1")
				tit[this.index].innerHTML = tit1[this.index].value
				var otc = getClassname("con","tc")
				var ott = getClassname("con","tt")
				for(var j=0;j<otc.length;j++){
					otc[j].index = j
					otc[j].innerHTML = ott[j].value
				}
				var fx = getClassname("con","feixuan")[this.index]
				var fb = getClassname("con","fb")[this.index]
				if(fx.checked == true){
					fb.innerHTML = "(必选)"
				}else{
					fb.innerHTML = "(非选)"
				}
			}
		}
			/*鼠标以上添加边框*/
		var con01 = getClassname("con","con01")
		var con001 = getClassname("con","con001")
		var bj = getClassname("con","bianji")
		var bj01 = getClassname("con","bianji01")
		var bj02 = getClassname("con","bianji02")
		for(var i=0;i<con01.length;i++){
			con01[i].index = i;
			
			con01[i].onmouseover = function(){
				if(con001[this.index].style.display=="none"){
					con01[this.index].classList.add("border")
					bj[this.index].style.display = "block"
					
				}
			}
		}
		for(var i=0;i<bj01.length;i++){
			bj01[i].index = i;
			bj01[i].onclick = function(){
				con001[this.index].style.display="block"
				con01[this.index].classList.remove("border")
				bj[this.index].style.display = "none"
			}
			bj02[i].onclick = function(){
				con.removeChild(this.parentNode.parentNode)
			}
		}
		for(var i=0;i<con01.length;i++){
			con01[i].index = i;
			con01[i].onmouseout = function(){
				if(con001[this.index].style.display=="none"){
					con01[this.index].classList.remove("border")
					bj[this.index].style.display = "none"
					
				}
			}
		}
	}
	Is[2].onclick = function(){
		var box = document.createElement("div");
		Is[2].parentNode.parentNode.parentNode.appendChild(box);
		var div = document.createElement("div");
		div.innerHTML =`<div class="con01">
							<h6><span class="tit">14.个人肌肤状态详细说明(15到60字)</span><span class="fb"></span></h6>
							<div class="text">
								<textarea name="text" id="" cols="30" rows="10"></textarea>
							</div>
							<div class="con001">
								<div class="text01">
									<textarea name="text" id="" placeholder="标题" class="tit1" cols="30" rows="10"></textarea>
									<p>
										<input type="radio" name="feixuan" class="feixuan" id="" value="" />必选
									</p>
								</div>
								<input type="button" name="wc" value="完成" class="wancheng"/>
							</div>
							<div class="bianji"><span class="bianji01">编辑</span><span class="bianji02">删除</span></div>
						</div>`;
		box.appendChild(div);
		//	完成
		for(var i=0;i<wc.length;i++){
			wc[i].index = i
			wc[i].onclick = function none(){
				con001[this.index].style.display = "none"
				var tit = getClassname("con","tit")
				var tit1 = getClassname("con","tit1")
				tit[this.index].innerHTML = tit1[this.index].value
				var fx = getClassname("con","feixuan")[this.index]
				var fb = getClassname("con","fb")[this.index]
				if(fx.checked == true){
					fb.innerHTML = "(必选)"
				}else{
					fb.innerHTML = "(非选)"
				}
			}
		}
			/*鼠标以上添加边框*/
		var con01 = getClassname("con","con01")
		var con001 = getClassname("con","con001")
		var bj = getClassname("con","bianji")
		var bj01 = getClassname("con","bianji01")
		var bj02 = getClassname("con","bianji02")
		for(var i=0;i<con01.length;i++){
			con01[i].index = i;
			
			con01[i].onmouseover = function(){
				if(con001[this.index].style.display=="none"){
					con01[this.index].classList.add("border")
					bj[this.index].style.display = "block"
					
				}
			}
		}
		for(var i=0;i<bj01.length;i++){
			bj01[i].index = i;
			bj01[i].onclick = function(){
				con001[this.index].style.display="block"
				con01[this.index].classList.remove("border")
				bj[this.index].style.display = "none"
			}
			bj02[i].onclick = function(){
				con.removeChild(this.parentNode.parentNode)
			}
			
		}
		for(var i=0;i<con01.length;i++){
			con01[i].index = i;
			con01[i].onmouseout = function(){
				if(con001[this.index].style.display=="none"){
					con01[this.index].classList.remove("border")
					bj[this.index].style.display = "none"
					
				}
			}
		}
	}
	Is[3].onclick = function(){
		var box = document.createElement("div");
		Is[3].parentNode.parentNode.parentNode.appendChild(box);
		var div = document.createElement("div");
		div.innerHTML =`<div class="con01 con02">
							<h6><span class="red">*</span><span class="tit">13.面部肌肤侧面照片(右)</span><span class="fb"></span></h6>
							<p class="red">图片要求无美颜，清晰</p>
							<div class="text">
								<div class="file">
									<img src="img/xiao.jpg"/>
									<span>选择文件(不超过10M)</span>
									<input id="pic" type="file" name = 'pic' onchange = "selectFile()"/>
								</div>
								<div id = "result"></div>
							</div>
							<div class="con001">
								<div class="text01">
									<textarea name="text" id="" placeholder="标题" class="tit1" cols="30" rows="10"></textarea>
									<p>
										<input type="radio" name="feixuan" class="feixuan" id="" value="" />必选
									</p>
								</div>
								<input type="button" name="wc" value="完成" class="wancheng"/>
							</div>
							<div class="bianji"><span class="bianji01">编辑</span><span class="bianji02">删除</span></div>
						</div>`;
		box.appendChild(div);
		//	完成
		for(var i=0;i<wc.length;i++){
			wc[i].index = i
			wc[i].onclick = function none(){
				con001[this.index].style.display = "none"
				var tit = getClassname("con","tit")
				var tit1 = getClassname("con","tit1")
				tit[this.index].innerHTML = tit1[this.index].value
				var fx = getClassname("con","feixuan")[this.index]
				var fb = getClassname("con","fb")[this.index]
				if(fx.checked == true){
					fb.innerHTML = "(必选)"
				}else{
					fb.innerHTML = "(非选)"
				}
			}
		}
			/*鼠标以上添加边框*/
		var con01 = getClassname("con","con01")
		var con001 = getClassname("con","con001")
		var bj = getClassname("con","bianji")
		var bj01 = getClassname("con","bianji01")
		var bj02 = getClassname("con","bianji02")
		for(var i=0;i<con01.length;i++){
			con01[i].index = i;
			
			con01[i].onmouseover = function(){
				if(con001[this.index].style.display=="none"){
					con01[this.index].classList.add("border")
					bj[this.index].style.display = "block"
					
				}
			}
		}
		for(var i=0;i<bj01.length;i++){
			bj01[i].index = i;
			bj01[i].onclick = function(){
				con001[this.index].style.display="block"
				con01[this.index].classList.remove("border")
				bj[this.index].style.display = "none"
			}
			bj02[i].onclick = function(){
				con.removeChild(this.parentNode.parentNode)
			}
		}
		for(var i=0;i<con01.length;i++){
			con01[i].index = i;
			con01[i].onmouseout = function(){
				if(con001[this.index].style.display=="none"){
					con01[this.index].classList.remove("border")
					bj[this.index].style.display = "none"
					
				}
			}
		}
	}
	
	//添加图片文件
	var form = new FormData();//通过HTML表单创建FormData对象
	var url = '127.0.0.1:8080/'
	function selectFile(){
	    var files = document.getElementById('pic').files;
	//	            console.log(files[0]);
	    var file = files[0];
	    //把上传的图片显示出来
	    var reader = new FileReader();
	    // 将文件以Data URL形式进行读入页面
	//	            console.log(reader);
	    reader.readAsBinaryString(file);
	    reader.onload = function(){
	        var result = document.getElementById("result");
	        var src = "data:" + file.type + ";base64," + window.btoa(this.result);
	        result.innerHTML = '<img src ="'+src+'"/>';
	    }
	//	            console.log('file',file);
	//	            console.log(form.get('file'));
	}
	

	
	
	
	
	
	
	
	
	
	
	
	
	
}