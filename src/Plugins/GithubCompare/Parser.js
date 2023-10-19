matches.forEach(function(m)
{
  var tag = addSelfClosingTag(config.tagName, m[0][1], m[0][0].length, -10);

  tag.setAttributes({
    'repo': m[1][0],
    'base': m[2][0],
    'head': m[3][0]
  });
});
