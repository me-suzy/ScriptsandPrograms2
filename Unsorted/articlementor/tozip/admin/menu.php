<%

Sub WriteFakeButton( sRef, sText )
	Response.Write " <b><a href=""" & sRef & """" & "><font color=#000066 size=""2"" face=""Arial,Helvetica"">" & "  " & sText &  "</font></a></b>"
End Sub


Sub WriteMenuItem( sRef, sText )
	Response.Write " <a href=""" & sRef & """" & "><font color=#000066 size=""1"" face=""Arial,Helvetica"">" & "  " & sText &  "</font></a><br>"
End Sub

Sub AdAdminWriteMenu
	Response.Write "<b>PostcardMentor Menu</b><br>"
	WriteMenuItem 	"cats.asp", "Categories"
	WriteMenuItem 	"postcards.asp", "Postcards"
	WriteMenuItem 	"deloldcards.asp", "Delete old card(30days)"
End Sub


%>