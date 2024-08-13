async function postBodyData(url, body) {
  let obj;
  const res = await fetch(url, {
    // Adding method type
    method: "POST",
    // Adding body or contents to send
    body: body,
    // Adding headers to the request
    headers: {
      "Content-type": "application/json; charset=UTF-8",
    },
  });
  obj = await res.json();
  return obj;
}
