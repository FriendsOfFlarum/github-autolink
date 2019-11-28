matches.forEach(function(m)
{
  var tag = addSelfClosingTag(config.tagName, m[0][1], m[0][0].length, -10);

  tag.setAttributes({
    'repo':  m[1][0] || m[5][0],
    'commit': m[2][0] || m[6][0],
    'comment': m[3][0],
    'diff': m[4][0],
  });
});
