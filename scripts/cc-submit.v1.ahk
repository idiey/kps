#NoEnv
#SingleInstance Force

; Hotkeys:
; - Ctrl+Shift+Enter: Always route + submit
; - Ctrl+Shift+B: Toggle Brain Mode (Enter will route + submit)
; Use Ctrl+Enter in some chats? set submitKey := "^{Enter}"
submitKey := "{Enter}"
nodeExe := "node"
root := A_ScriptDir "\.."
routeScript := root "\scripts\control-center-route.mjs"
tempFile := A_Temp "\cc_prompt.txt"
brainMode := false

^+Enter::
    ClipSaved := ClipboardAll
    Clipboard :=
    Send ^a
    Send ^c
    ClipWait, 0.5
    text := Clipboard
    Clipboard := ClipSaved

    if (Trim(text) = "") {
        Send %submitKey%
        return
    }

    FileDelete, %tempFile%
    FileAppend, %text%, %tempFile%, UTF-8
    RunWait, % nodeExe " \"" routeScript "\" --text-file=\"" tempFile "\" --source=hotkey",, Hide
    Send {End}
    Send %submitKey%
return

^+b::
    brainMode := !brainMode
    state := brainMode ? "ON" : "OFF"
    TrayTip, Control Center, Brain Mode: %state%, 1
return

#If brainMode
Enter::
    Gosub, ^+Enter
return
#If
