matches.forEach(function(m)
{
  var tag = addSelfClosingTag(config.tagName, m[0][1], m[0][0].length, -10);

  var repoPath = m[2] ? m[2][0] : '';

  tag.setAttributes({
    'repo':  m[1][0] || m[5][0],
    'repopath': repoPath
  });
});
