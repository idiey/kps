#Requires AutoHotkey v2.0
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

Hotkey "^+Enter", SubmitWithCC
Hotkey "^+b", ToggleBrainMode

#HotIf brainMode
Enter::SubmitWithCC()
#HotIf

SubmitWithCC(*) {
    ClipSaved := ClipboardAll()
    Clipboard := ""
    Send "^a"
    Send "^c"
    ClipWait 0.5
    text := Clipboard
    Clipboard := ClipSaved

    if (StrLen(Trim(text)) = 0) {
        Send submitKey
        return
    }

    try {
        FileDelete tempFile
    }
    FileAppend text, tempFile, "UTF-8"
    RunWait Format('{} "{}" --text-file="{}" --source=hotkey', nodeExe, routeScript, tempFile), , "Hide"
    Send "{End}"
    Send submitKey
}

ToggleBrainMode(*) {
    global brainMode
    brainMode := !brainMode
    state := brainMode ? "ON" : "OFF"
    TrayTip "Control Center", "Brain Mode: " state, 1
}
