# PowerShell script to convert XLSX to CSV
# This requires Excel to be installed or COM objects

Add-Type -AssemblyName System.IO.Compression.FileSystem

function Convert-XlsxToCsv {
    param(
        [string]$XlsxPath = "Book1.xlsx",
        [string]$OutputPath = "Book1.csv"
    )

    try {
        # Try using COM object (requires Excel installed)
        $excel = New-Object -ComObject Excel.Application
        $excel.Visible = $false
        $excel.DisplayAlerts = $false
        
        $workbook = $excel.Workbooks.Open((Resolve-Path $XlsxPath))
        $worksheet = $workbook.Worksheets.Item(1)
        
        $worksheet.SaveAs((Resolve-Path $OutputPath).Path, 6) # 6 = CSV format
        
        $workbook.Close($false)
        $excel.Quit()
        
        [System.Runtime.Interopservices.Marshal]::ReleaseComObject($excel) | Out-Null
        
        Write-Host "Converted $XlsxPath to $OutputPath successfully!"
        return $true
    }
    catch {
        Write-Host "Error: $_"
        Write-Host "Note: Excel must be installed to use COM objects, or enable PHP zip extension"
        return $false
    }
}

Convert-XlsxToCsv

