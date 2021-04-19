// JavaScript Document
var i = 2; //定义密码输错的次数

//判断输入的卡号是不是数字类型
//返回true，证明不是数字；返回false，证明是数字
// function checkNumber(account){
// 	var pattern=/^[0-9]*[1-9][0-9]*$/;
// 	return pattern.test(account);
// 	// return isNaN(account);
// }

//判断输入的卡号和密码是否为空
function checkNull(password){
 	if(password.length>0){
 		return true;
 	}
 	return false;
 }

//登录事件
function login(){
// 	var account=document.getElementById("account").value;
 	var password=document.getElementById("password").value;
	
 	if(!checkNull(password)){
 		alert("密码不能为空!");
 		return; //终止登录方法，下面的代码不会执行
 	}
// 	if(!checkNumber(account)){
// 		alert("卡号必须为数字！");
// 		return;
// 	}
 	if(i>0 && password=="123"){
 		window.location.href="index.html";
 	}else{
 		if(i == 0){
 			alert("当前银行卡被锁定！即将吞卡！");
 			return;
 		}
 		alert("你还剩下"+i+"次输入卡号和密码的机会");
 		i--;
 		return;
 	}
 }
 //取款
function withdraw(){
 	var daybalance = parseFloat(document.getElementById("daybalance").value); //获取当日限额，并将其转换为数字	
 	var balance = parseFloat(document.getElementById("balance").value); //获取余额，并将其转换为数字
 	var withdraw = document.getElementById("withdraw").value;

	if(!checkNull(withdraw)){
 		alert("金额不能为空!");
 	}

	//判断取款是否大于余额
 	if(parseFloat(withdraw) > balance){
 		alert("余额不足！");
 	}
	
	//判断取款是否大于当日限额
 	if(parseFloat(withdraw) > daybalance){
 		alert("超过今日限额！");
 	}

	//判断取款是否小于余额和当日限额
	if(parseFloat(withdraw) <= balance && parseFloat(withdraw) <= daybalance){
 		alert("取款成功！");
		balance-=parseFloat(withdraw);
 		document.getElementById("balance").value=balance;
		daybalance-=parseFloat(withdraw);
 		document.getElementById("daybalance").value=daybalance;
		clearInterval(withdraw);
 	}
	
//存款
// function deposit(){
// 	var balance = parseFloat(document.getElementById("balance").value); //获取余额，并将其转换为数字
// 	var deposit = document.getElementById("deposit").value;
//
// 	if(!deposit.length>0){
// 		alert("请输入您要存款的金额");
// 		return;
// 	}
// 	if(checkNumber(deposit)){
// 		alert("请输入数字");
// 		return;
// 	}
//
// 	balance+=parseFloat(deposit);
// 	document.getElementById("balance").value=balance;  //修改存款完成以后显示的余额
//
// }

}