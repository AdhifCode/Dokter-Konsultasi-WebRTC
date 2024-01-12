export default async function ({ store, redirect }) {
  console.log('Middleware: authenticated')

  await store.dispatch('meet/checkPermission')

  const Proof = store.state.meet.cookieData

  console.log('Permission state:', Proof)

  if (Proof === undefined || Proof === null) {
    console.log('Rejected')
    return redirect('/mainpage')
  }
}
