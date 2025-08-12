@ECHO OFF
vbc /target:library /optimize /r:system.web.dll,system.data.dll,system.dll,system.xml.dll /out:dotNetBB.dll *.vb
ECHO dotNetBB.dll has been built successfully.