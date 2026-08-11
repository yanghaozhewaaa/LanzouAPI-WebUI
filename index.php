<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>蓝奏云直链解析</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{background:#f3f4f6;font-family:system-ui,sans-serif;padding:40px 16px;}
.wrap{max-width:640px;margin:0 auto;background:#fff;padding:32px;border-radius:16px;box-shadow:0 4px 20px #00000012;}
h2{text-align:center;margin-bottom:24px;color:#1f2937;}
.item{margin-bottom:18px;}
label{display:block;margin-bottom:6px;font-weight:500;}
input{width:100%;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:15px;outline:none;}
input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.15);}
#submitBtn{width:100%;padding:13px;background:#2563eb;color:#fff;border:none;border-radius:10px;font-size:16px;cursor:pointer;}
#submitBtn:disabled{background:#94a3b8;}
.result{margin-top:24px;display:none;}
.box{background:#f9fafb;padding:16px;border-radius:10px;margin-bottom:14px;word-break:break-all;}
.box h4{margin-bottom:8px;}
.tip{font-size:13px;color:#6b7280;margin-bottom:8px;}
.copy-btn{padding:7px 12px;margin-top:8px;background:#10b981;color:#fff;border:none;border-radius:8px;cursor:pointer;}
.err{background:#fee2e2;color:#b91c1c;padding:12px;border-radius:10px;margin-top:12px;display:none;}
</style>
</head>
<body>
<div class="wrap">
    <h2>蓝奏云直链解析</h2>
    <div class="item">
        <label>粘贴分享链接</label>
        <input id="link" placeholder="蓝奏云分享链接">
    </div>
    <div class="item">
        <label>分享密码（无密码留空）</label>
        <input id="pwd" placeholder="密码">
    </div>
    <button id="submitBtn">开始解析</button>
    <div id="errBox" class="err"></div>

    <div class="result" id="resWrap">
        <div class="box">
            <h4>文件名</h4>
            <div id="resName"></div>
        </div>
        <div class="box">
            <h4>文件大小</h4>
            <div id="resSize"></div>
        </div>
        <div class="box">
            <h4>临时直链（会过期）</h4>
            <div class="tip">蓝奏返回，数小时失效</div>
            <div id="tempUrl"></div>
            <button class="copy-btn" data-id="tempUrl">复制临时直链</button>
        </div>
        <div class="box">
            <h4>永久调用链接</h4>
            <div class="tip">每次访问重新解析，type=down直接下载</div>
            <div id="permUrl"></div>
            <button class="copy-btn" data-id="permUrl">复制永久链接</button>
        </div>
    </div>
</div>

<script>
const API_PATH = "./api/index.php";

const linkInp = document.querySelector("#link");
const pwdInp = document.querySelector("#pwd");
const submitBtn = document.querySelector("#submitBtn");
const resWrap = document.querySelector("#resWrap");
const errBox = document.querySelector("#errBox");

function showErr(text){
    errBox.style.display="block";
    errBox.innerText=text;
}
function hideErr(){
    errBox.style.display="none";
}

submitBtn.onclick = async ()=>{
    hideErr();
    resWrap.style.display="none";
    const url = linkInp.value.trim();
    const pwd = pwdInp.value.trim();
    if(!url){showErr("请输入蓝奏分享链接");return;}
    submitBtn.disabled=true;
    try{
        let fetchUrl = `${API_PATH}?url=${encodeURIComponent(url)}`;
        if(pwd) fetchUrl += `&pwd=${encodeURIComponent(pwd)}`;
        const resp = await fetch(fetchUrl);
        const data = await resp.json();
        if(data.code!==200){
            showErr(data.msg||"解析失败");
            return;
        }
        //适配你接口真实字段：filesize、downUrl
        document.querySelector("#resName").innerText = data.name;
        document.querySelector("#resSize").innerText = data.filesize;
        document.querySelector("#tempUrl").innerText = data.downUrl;

        const permanent = location.origin + "/api/index.php?url="+encodeURIComponent(url)+(pwd?"&pwd="+encodeURIComponent(pwd):"")+"&type=down";
        document.querySelector("#permUrl").innerText = permanent;
        resWrap.style.display="block";
    }catch(e){
        showErr("请求失败，请确认api接口可访问");
        console.error(e);
    }finally{
        submitBtn.disabled=false;
    }
};

document.querySelectorAll(".copy-btn").forEach(btn=>{
    btn.onclick = async function(){
        const tid = this.dataset.id;
        const txt = document.getElementById(tid).innerText;
        await navigator.clipboard.writeText(txt);
        const old = this.innerText;
        this.innerText="✅已复制";
        setTimeout(()=>this.innerText=old,1200);
    }
})
</script>
</body>
</html>
