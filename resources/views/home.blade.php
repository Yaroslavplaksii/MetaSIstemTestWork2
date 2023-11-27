@extends('layout')
@section('content')
<form>
    @csrf()
    <input type="file" name="largefile">
    <input type="button" value="upload" onclick="sendFile()" id="upload">
</form>
<script>
    const url = 'http://127.0.0.1:8000/upload';
    const size = 20000;
    let buffer = null;
    let file = null;

    function getExtention(filepath) {
        let dpos = filepath.lastIndexOf('.');
        return filepath.substring(dpos + 1);
    }
    function sendFile() {
            let i=0;
            let j=0; 
            let newFileName = new Date().getTime();
            let reader = new FileReader();
            file = document.querySelector('[name="largefile"]').files[0];
            let ext = getExtention(file.name);
        
            reader.onload = function(e) {
                buffer = new Uint8Array(e.target.result);
                let uNewFileName = newFileName + '.' + ext;              
                uploadFile(uNewFileName, i, j);
            };
            reader.readAsArrayBuffer(file);
    }
    function uploadFile(uNewFileName, i, j) {
            let countPart = Math.ceil(buffer.length / size);
            let fd = new FormData();
            fd.append('newFileName', uNewFileName);
            fd.append('filename', [file.name, i+1, 'of', buffer.length].join('-'));
            fd.append('data', new Blob([buffer.subarray(i, i + size)]));
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open("POST", url, true);
            xmlhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('[name="_token"]').value);
            xmlhttp.setRequestHeader('Accept-Ranges', 'arraybuffer');
            xmlhttp.setRequestHeader('Response-Type', 'arraybuffer');
            xmlhttp.setRequestHeader('Range', `bytes=${buffer.length}-${i + size}`);
            xmlhttp.onload = function(event) {
                if (event.currentTarget.response == 200) {
                    i += size;
                    j++;
                    if (j <= countPart) {
                        uploadFile(uNewFileName, i, j);
                    }
                }
            };
            xmlhttp.upload.addEventListener("error", (event) => {
                uploadFile(uNewFileName, i, j);
            });
            xmlhttp.send(fd);
    }
    
   
</script>
@endsection