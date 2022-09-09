Error in {{ env("APP_NAME") }}
<b>Description: </b> <code>{{ $description }}</code>
<b>File: </b> <code>{{ $file }}</code>
<b>Line: </b> <code>{{ $line }}</code>
<b>Route: </b> <code>{{ $route }}</code>
<b>Headers: </b> <code>{{ json_encode($headers, JSON_PRETTY_PRINT) }}</code>

